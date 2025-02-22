<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Moncton/connect.php'); 

// Function to load employees from the database, sorting active ones at the top
function loadEmployees() {
    global $con;
    $query = "SELECT * FROM employee ORDER BY SetStatus DESC, Emp_ID ASC"; 
    $result = mysqli_query($con, $query);

    if (!$result) {
        die(json_encode(['status' => 'error', 'message' => 'Error fetching employees: ' . mysqli_error($con)]));
    }

    $employees = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }

    return $employees;
}



// Function to update the temporary password in the database
function updateTempPassword($id, $newPassword)
{
    global $con;
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the password

    $query = "UPDATE employee SET TempPassword = ? WHERE Emp_ID = ?";
    $stmt = mysqli_prepare($con, $query);
    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Database error: ' . mysqli_error($con)];
    }
    mysqli_stmt_bind_param($stmt, 'si', $hashedPassword, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        return ['status' => 'success', 'message' => 'Temporary password updated successfully.'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to update temporary password.'];
    }
}

// Function to destroy the temporary password
function destroyTempPassword($id)
{
    global $con;

    $query = "UPDATE employee SET TempPassword = '0' WHERE Emp_ID = ?";
    $stmt = mysqli_prepare($con, $query);
    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Database error: ' . mysqli_error($con)];
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        return ['status' => 'success', 'message' => 'Temporary password destroyed successfully.'];
    } else {
        return ['status' => 'error', 'message' => 'Failed to destroy temporary password.'];
    }
}


// Function to activate or deactivate an employee
function toggleEmployeeStatus($id, $status)
{
    global $con;
    //$newStatus = ($status === 'Active') ? 'Inactive' : 'Active'; // Updated to match DB capitalization

    $query = "UPDATE employee SET SetStatus = ? WHERE Emp_ID = ?"; // Updated field name to SetStatus
    $stmt = mysqli_prepare($con, $query);
    if (!$stmt) {
        die(json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($con)]));
    }
    mysqli_stmt_bind_param($stmt, 'si', $status, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result ? $status : false;
}

// Handle the action based on the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['action'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No action specified.']);
        exit;
    }

    $action = $_POST['action'];

    if ($action === 'generatePassword') {
        if (!isset($_POST['userId'], $_POST['password'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing user ID or password.']);
            exit;
        }

        $userId = (int) $_POST['userId'];
        $newPassword = $_POST['password'];

        if (updateTempPassword($userId, $newPassword)) {
            echo json_encode(['status' => 'success', 'password' => $newPassword]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update temporary password.']);
        }
    } elseif ($action === 'destroyTempPassword') {
        if (!isset($_POST['userId'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing user ID.']);
            exit;
        }

        $userId = (int) $_POST['userId'];

        if (destroyTempPassword($userId)) {
            echo json_encode(['status' => 'success', 'message' => 'Temporary password destroyed successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to destroy temporary password.']);
        }
    } elseif ($action === 'toggleStatus') {
        if (!isset($_POST['userId'], $_POST['status'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing user ID or status.']);
            exit;
        }

        $userId = (int) $_POST['userId'];
        $currentStatus = $_POST['status'];

        $newStatus = toggleEmployeeStatus($userId, $currentStatus);
        if ($newStatus !== false) {
            echo json_encode(['status' => 'success', 'newStatus' => $newStatus]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update employee status.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
    }
} else {
    http_response_code(405);
}
?>