<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["EmailCode"])) {
    $errors = [];

    // Validate Email
    $email = trim($_POST["EmailCode"]);
    if (empty($email)) {
        $errors[] = "Please enter your Email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($con, $email);

        // Check if email exists in the database
        $sql = "SELECT `Email` FROM `employee` WHERE `Email` = '$email'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) === 1) {
            // Email exists, redirect to InsertCode.php with success message
            $msg = "Check your email for OTP.";
            header("location:InsertCode.php?message=" . urlencode($msg));
            exit();
        } else {
            $errors[] = "Email does not exist.";
        }
    }

    // If errors exist, display them
    if (!empty($errors)) {
        $msg = implode(" ", $errors); // Combine errors into one message
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
