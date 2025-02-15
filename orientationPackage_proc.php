<?php
include("connect.php");

// connection declaration
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// connection check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// if form is request then process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // form data
    $staffFirstName = $_POST['staff_first_name'] ?? '';
    $staffLastName = $_POST['staff_last_name'] ?? '';
    $house = $_POST['house'] ?? '';
    $category = $_POST['category'] ?? '';
    $supervisorFirstName = $_POST['supervisor_first_name'] ?? '';
    $supervisorLastName = $_POST['supervisor_last_name'] ?? '';
    $shifts = $_POST['shift_date'] ?? [];
    $shiftTimes = $_POST['shift_time'] ?? [];
    $shiftLocations = $_POST['shift_location'] ?? [];
    $tasks = $_POST['task_description'] ?? [];
    $taskCompleted = $_POST['task_completed'] ?? [];
    $staffSignature = $_POST['staff_signature'] ?? '';
    $feedback = $_POST['feedback'] ?? '';
    $orientationform_date = $_POST['orientationform_date'] ?? [];
    $orientationform_time = $_POST['orientationform_time'] ?? [];
    
    // validate fields
    if (empty($staffFirstName) || empty($staffLastName) || empty($house) || empty($category) || empty($supervisorFirstName) || empty($supervisorLastName)) {
        echo "All fields are required.";
        exit;
    }

    // get employee_id
    $employeeId = null;
    $employeeStmt = $conn->prepare("SELECT ID FROM employee WHERE FirstName = ? AND LastName = ?");
    $employeeStmt->bind_param("ss", $staffFirstName, $staffLastName);
    $employeeStmt->execute();
    $employeeResult = $employeeStmt->get_result();
    
    if ($employeeResult->num_rows > 0) {
        $employeeRow = $employeeResult->fetch_assoc();
        $employeeId = $employeeRow['ID'];
    } else {
        echo "No employee found with the given name.";
        exit;
    }
    
    $employeeStmt->close();

    // start transaction
    $conn->begin_transaction();

    try {
        // first insert into orientation table
        $stmt = $conn->prepare("INSERT INTO orientation (staff_firstname, staff_lastname, house, category, supervisor_firstname, supervisor_lastname, feedback, signature, orientation_date, orientationform_time, employee_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $staffFirstName, $staffLastName, $house, $category, $supervisorFirstName, $supervisorLastName, $feedback, $staffSignature, $orientationform_date[0], $orientationform_time[0], $employeeId);

        if ($stmt->execute()) {
            // get last inserted id
            $orientationId = $stmt->insert_id;

            // insert into orientation_shifts table
            $shiftStmt = $conn->prepare("INSERT INTO orientation_shifts (orientation_id, shift_date, time, location) VALUES (?, ?, ?, ?)");
            $shiftIds = [];
            foreach ($shifts as $index => $shiftDate) {
                $shiftTime = $shiftTimes[$index] ?? '';
                $shiftLocation = $shiftLocations[$index] ?? '';
                $shiftStmt->bind_param("isss", $orientationId, $shiftDate, $shiftTime, $shiftLocation);
                $shiftStmt->execute();
                $shiftIds[] = $shiftStmt->insert_id;
                $shiftStmt->reset();
            }

            // insert tasks
            $taskStmt = $conn->prepare("INSERT INTO orientation_tasks (orientation_id, shift_id, task, complete) VALUES (?, ?, ?, ?)");
            foreach ($tasks as $index => $taskDescription) {
                $taskCompletedStatus = isset($taskCompleted[$index]) ? 1 : 0;
                $shiftId = $shiftIds[$index] ?? null;
                if ($shiftId) {
                    $taskStmt->bind_param("iisi", $orientationId, $shiftId, $taskDescription, $taskCompletedStatus);
                    $taskStmt->execute();
                    $taskStmt->reset();
                }
            }

            // commit
            $conn->commit();
            echo "Orientation package submitted successfully.";

            // close statements
            $shiftStmt->close();
            $taskStmt->close();
            $stmt->close();
        } else {
            throw new Exception("Error inserting into orientation table: " . $stmt->error);
        }
    } catch (Exception $e) {
        // rollback if error
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }

    // debugging
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // close conn
    $conn->close();
}
?>
