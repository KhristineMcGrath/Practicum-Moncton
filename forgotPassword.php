<!DOCTYPE html>
<?php
include("connect.php");
include("Alert.php");

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="includes/forgotPassword.css">

</head>

<body>
    <div class="login-container">
        <form class="login-form" action="forgotPassword_proc.php" method="POST">
            <h2>Password Reset</h2>
            <div class="form-group">
                <label for="EmailCode">Email</label>
                <input type="text" id="EmailCode" name="EmailCode" placeholder="Enter your Email" required>
            </div>
            <button type="submit" class="btn">Send Code</button>
        </form>
    </div>
</body>

</html>