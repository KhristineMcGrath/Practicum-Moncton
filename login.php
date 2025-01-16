<!DOCTYPE html>
<?php
include("connect.php");
include("Alert.php");
?>
<link rel="stylesheet" href="login.css">
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCRI Login</title>
    <!-- Linking the external CSS file -->
    <link rel="stylesheet" href="includes/login.css">

    <script>
        //show password function
        function myFunction() {
            var password = document.getElementById("password");
            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
        }
    </script>
</head>

<body>
    <div class="login-container">
        <h1>MCRI Login</h1>
        <form class="login-form" action="login_proc.php" method="POST">
            <div class="form-group">
                <select id="role" name="role" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="admin">Admin</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="member">Member</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <button type="button" class="toggle-password" onclick="myFunction()">Show</button>
                <a class="nav-link" href="forgotPassword.php">Forgot Password</a>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>

</html>