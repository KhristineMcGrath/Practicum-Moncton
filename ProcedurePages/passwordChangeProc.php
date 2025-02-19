<?php
session_start();
include('../connect.php');

if (isset($_POST['new_password'], $_POST['confirm_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $msg = "Passwords do not match.";
        header("Location: passwordChange.php?message=" . urlencode($msg));
        exit();
    }

    $email = $_SESSION['reset_email'];

    // Update the password in the database
    //$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE `employee` SET `Password` = ? WHERE `Email` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $newPassword, $email);

    if ($stmt->execute()) {
        $msg = "Password changed successfully.";
        header("Location: ../Login.php?message=" . urlencode($msg));
        exit();
    } else {
        $msg = "An error occurred. Please try again.";
        header("Location: passwordChange.php?message=" . urlencode($msg));
        exit();
    }
} else {
    $msg = "Invalid request.";
    header("Location: ../Login.php?message=" . urlencode($msg));
    exit();
}
?>
