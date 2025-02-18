<?php
include("connect.php");

// connection declaration
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// connection check
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
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
    $employeeStmt = $con->prepare("SELECT Emp_ID FROM employee WHERE FirstName = ? AND LastName = ?");
    $employeeStmt->bind_param("ss", $staffFirstName, $staffLastName);
    $employeeStmt->execute();
    $employeeResult = $employeeStmt->get_result();
    
    if ($employeeResult->num_rows > 0) {
        $employeeRow = $employeeResult->fetch_assoc();
        $employeeId = $employeeRow['Emp_ID'];
    } else {
        echo "No employee found with the given name.";
        exit;
    }
    
    $employeeStmt->close();

    // start transaction
    $con->begin_transaction();

    try {
        // insert into orientation table using stored procedure
        $stmt = $con->prepare("CALL InsertOrientation(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @orientationId)");
        $stmt->bind_param("ssssssssssi", $staffFirstName, $staffLastName, $house, $category, $supervisorFirstName, $supervisorLastName, $feedback, $staffSignature, $orientationform_date[0], $orientationform_time[0], $employeeId);
        $stmt->execute();
        $stmt->close();

        // get the last inserted orientation ID
        $result = $con->query("SELECT @orientationId AS orientationId");
        $row = $result->fetch_assoc();
        $orientationId = $row['orientationId'];

        // insert into orientation_shifts table using stored procedure
        foreach ($shifts as $index => $shiftDate) {
            $shiftTime = $shiftTimes[$index] ?? '';
            $shiftLocation = $shiftLocations[$index] ?? '';
            $stmt = $con->prepare("CALL InsertOrientationShift(?, ?, ?, ?, @shiftId)");
            $stmt->bind_param("isss", $orientationId, $shiftDate, $shiftTime, $shiftLocation);
            $stmt->execute();
            $stmt->close();

            // get the last inserted shift ID
            $result = $con->query("SELECT @shiftId AS shiftId");
            $row = $result->fetch_assoc();
            $shiftId = $row['shiftId'];

            // insert tasks associated with this shift
            if (isset($tasks[$index])) {
                foreach ($tasks[$index] as $taskIndex => $taskDescription) {
                    $taskCompletedStatus = isset($taskCompleted[$index][$taskIndex]) ? 1 : 0;
                    $taskStmt = $con->prepare("CALL InsertOrientationTask(?, ?, ?, ?)");
                    $taskStmt->bind_param("iisi", $orientationId, $shiftId, $taskDescription, $taskCompletedStatus);
                    $taskStmt->execute();
                    $taskStmt->close();
                }
            }
        }
        
        // commit
        $con->commit();
        echo "Orientation package submitted successfully.";
    } catch (Exception $e) {
        // rollback if error
        $con->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }

    // debugging
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // close conn
    $con->close();
}
?>
