<?php
include('../connect.php');
session_start(); // Start the session

if (isset($_POST["username"])) {
    $username = mysqli_real_escape_string($con, $_POST["username"]);

    // Query to select the Password, TempPassword, and SetupPassword fields
    $sql = "SELECT `Username`, `Password`, `TempPassword`, `SetupPassword`, `Email`, `Status`, `role`
            FROM `employee`
            WHERE `Username` = '$username'";

    $result = mysqli_query($con, $sql);

    if (mysqli_affected_rows($con) == 1) {
        $rows = mysqli_fetch_assoc($result);
        $password = $rows["Password"];  
        $tempPassword = $rows["TempPassword"]; 
        $setupPassword = $rows["SetupPassword"]; 
        $status = $rows["Status"];
        $role = $rows["role"];

        // Check if the account is inactive
        if ($status === 'Inactive') {
            $msg = "Account currently not activated. Please contact your supervisor.";
            header("location:../Login.php?message=" . urlencode($msg));
            exit();
        }

        // First, check if the plain password matches (not hashed)
        if ($_POST["password"] === $password) {
            redirectBasedOnRole($role);
            exit();
        }

        // If the plain password doesn't match, check if TempPassword (hashed) matches
        elseif (!empty($tempPassword) && password_verify($_POST["password"], $tempPassword)) {
            // TempPassword matches, log in the user and reset TempPassword to '0'
            $updateSql = "UPDATE `employee` SET `TempPassword` = '0' WHERE `Username` = '$username'";
            mysqli_query($con, $updateSql);

            // Proceed with role-based redirection
            redirectBasedOnRole($role);
            exit();
        }

        // If the plain password doesn't match, and TempPassword doesn't match, check if SetupPassword (hashed) matches
        elseif (!empty($setupPassword) && password_verify($_POST["password"], $setupPassword)) {
            // SetupPassword matches, store username in session
            $_SESSION['username'] = $username;

            // Redirect to ConfirmNewPassword.php
            header("location:../ConfirmNewPassword.php");
            exit();
        }

        // If all password checks fail, show an error message
        $msg = "Please check your username or password.";
        header("location:../Login.php?message=" . urlencode($msg));
        exit();
    } else {
        // If the username doesn't exist
        $msg = "Please check your username or password.";
        header("location:../Login.php?message=" . urlencode($msg));
        exit();
    }
} else {
    // Prevent direct access to this page
    $msg = "CAN'T ACCESS THIS PAGE DIRECTLY";
    header("location:../Login.php?message=" . urlencode($msg));
    exit();
}

function redirectBasedOnRole($role) {
    // Redirect the user based on their role
    switch ($role) {
        case 'Admin':
            header("location:../AdminDash.php");
            break;
        case 'Member':
            header("location:../MemberDash.php");
            break;
        case 'Supervisor':
            header("location:../SuperDash.php");
            break;
        default:
            // Handle invalid roles
            $msg = "Invalid role. Access denied.";
            header("location:../Login.php?message=" . urlencode($msg));
            break;
    }
    exit();
}
?>
