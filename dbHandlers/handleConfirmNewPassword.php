<?php
include("../connect.php");  // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate passwords
    if ($newPassword !== $confirmPassword) {
        $msg = "Passwords do not match. Please try again.";
        header("location: ../ConfirmNewPassword.php?username=" . urlencode($username) . "&message=" . urlencode($msg));
        exit();
    }

    if (strlen($newPassword) < 6 || strlen($newPassword) > 25) {
        $msg = "Password must be between 6 and 25 characters long.";
        header("location: ../ConfirmNewPassword.php?username=" . urlencode($username) . "&message=" . urlencode($msg));
        exit();
    }

    $sql = "UPDATE employee SET Password = ?, SetupPassword = '0' WHERE UserName = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $newPassword, $username);

    if ($stmt->execute()) {
        $msg = "Password updated successfully. You can now log in with your new password.";
        header("location: ../Login.php?message=" . urlencode($msg));
    } else {
        $msg = "Error updating password. Please try again.";
        header("location: ../ConfirmNewPassword.php?username=" . urlencode($username) . "&message=" . urlencode($msg));
    }

    $stmt->close();
} else {
    $msg = "CAN'T ACCESS THIS PAGE DIRECTLY";
    header("location: ../Login.php?message=" . urlencode($msg));
    exit();
}
?>

