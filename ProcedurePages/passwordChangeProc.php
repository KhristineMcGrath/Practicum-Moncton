<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['reset_email'])) {
    $msg = "Session expired. Please try again.";
    header("Location: ../Login.php?message=" . urlencode($msg));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['new_password'], $_POST['confirm_password'])) {
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validate password match
    if ($newPassword !== $confirmPassword) {
        $msg = "Passwords do not match.";
        header("Location: ../passwordChange.php?message=" . urlencode($msg));
        exit();
    }

    $email = $_SESSION['reset_email'];

    // Hash the new password
    //$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Call the stored procedure
    $sql = "CALL UpdateEmployeePassword(?, ?)";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("ss", $email, $newPassword);  //chage the variable $newPassword with $hashedPassword for hash

        if ($stmt->execute()) {
            // Unset session variable to prevent reuse
            unset($_SESSION['reset_email']);

            $msg = "Password changed successfully.";
            header("Location: ../Login.php?message=" . urlencode($msg));
            exit();
        } else {
            $msg = "An error occurred. Please try again.";
        }
        $stmt->close();
    } else {
        $msg = "Database error. Please try again.";
    }
} else {
    $msg = "Invalid request.";
}

header("Location: ../passwordChange.php?message=" . urlencode($msg));
exit();
?>
