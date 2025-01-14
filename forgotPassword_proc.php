<?php
include("connect.php");
include("Alert.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["EmailCode"])) {
    $errors = [];

    // Validate Email
    if (empty($_POST["EmailCode"])) {
        $errors[] = "Please enter your Email.";
    } elseif (!filter_var($_POST["EmailCode"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($con, $_POST["EmailCode"]);

        // Check if email exists in the database
        $sql = "SELECT `Email` FROM `employee` WHERE `Email` = '$email'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) === 1) {
            // Redirect to InsertCode.php with success message
            $msg = "Check your email for OTP.";
            header("location:InsertCode.php?message=" . urlencode($msg));
            exit();
        } else {
            $errors[] = "Email does not exist.";
        }
    }

    if (!empty($errors)) {
        // Redirect back with error messages
        $msg = implode(" ", $errors); // Combine all errors into one message
        header("location:forgotPassword.php?message=" . urlencode($msg));
        exit();
    }
} else {
    // If accessed directly or without POST
    $msg = "Cannot access this page directly.";
    header("location:Login.php?message=" . urlencode($msg));
    exit();
}
?>
