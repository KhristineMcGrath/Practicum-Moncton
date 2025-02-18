<!DOCTYPE html>
<?php
session_start();
include("connect.php");
include("Alert.php");
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="includes/change_password.css">
</head>
<body>
    <div class="password-container">
        <form action="ProcedurePages/passwordChangeProc.php" method="POST">
            <h2>Change Password</h2>
            <p>Please enter and confirm your new password below.</p>
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new_password" placeholder="Enter new password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm new password" required>
            </div>
            <button type="submit" class="submit-btn">Change Password</button>
        </form>
    </div>
</body>
</html>