<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // form data
    $client_firstname = $_POST['client_first_name'] ?? '';
    $client_lastname = $_POST['client_last_name'] ?? '';
    $weekday = $_POST['weekday'] ?? '';
    $weekendvisit_date = $_POST['date'] ?? ''; 
    $staff_firstname = $_POST['staff_first_name'] ?? '';
    $staff_lastname = $_POST['staff_last_name'] ?? '';
    $note = $_POST['notes'] ?? '';
    $signature = $_POST['e_signature'] ?? '';
    $task_times = $_POST['time'] ?? [];
    $tasks = $_POST['tasks'] ?? [];
    $completed = array_map(function($value) {
        return $value === '1' ? 1 : 0;
    }, $_POST['completed'] ?? []);
    $formcreated = date("Y-m-d H:i:s");

    // get employee id
    $employeeQuery = "SELECT Emp_ID FROM employee WHERE FirstName = ? AND LastName = ?";
    $empStmt = $con->prepare($employeeQuery);
    $empStmt->bind_param("ss", $staff_firstname, $staff_lastname);
    $empStmt->execute();
    $empStmt->bind_result($employee_id);
    $empStmt->fetch();
    $empStmt->close();

    if (!$employee_id) {
        die("Error: Employee not found.");
    }

    // call weekend visit procedure
    $stmt = $con->prepare("CALL InsertWeekendForm(?, ?, ?, ?, ?, ?, ?, ?, ?, @weekend_visit)");
    $stmt->bind_param(
        "ssssssiss",  
        $client_firstname, 
        $client_lastname, 
        $staff_firstname,
        $staff_lastname,
        $weekday, 
        $weekendvisit_date, 
        $employee_id,  
        $formcreated, 
        $signature
    );

    if ($stmt->execute()) {
        // get weekend visit id
        $result = $con->query("SELECT @weekend_visit AS weekend_visit");
        $row = $result->fetch_assoc();
        $weekend_visit = $row['weekend_visit'];

        echo "New Weekend Visit created successfully, Weekend Visit ID: $weekend_visit<br>";

        // loop through tasks and insert each task individually
        foreach ($tasks as $index => $task) {
            // task time and completed status
            $task_time = $task_times[$index] ?? null; 
            $task_complete = $completed[$index] ?? 0; // 1 if checked, 0 if not

            $taskStmt = $con->prepare("CALL InsertWeekendTasks(?, ?, ?, ?, ?)");
            $taskStmt->bind_param(
                "isssi",
                $weekend_visit,  
                $task,          
                $task_time,     
                $note,           
                $task_complete   
            );

            // execute tasks, for each task in the form, i'm outputting the task id and weekend visit id for debugging
            if ($taskStmt->execute()) {
                echo "New Task created for Weekend Visit ID: $weekend_visit<br>";
            } else {
                echo "Error inserting task: " . $taskStmt->error . "<br>";
            }

            $taskStmt->close();
        }
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    // close statement and connection
    $stmt->close();
    $con->close();
}
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
?>
