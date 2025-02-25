<!DOCTYPE html>
<?php
include("connect.php");
include("Alert.php");
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- Linking the external CSS file -->
    <link rel="stylesheet" href="includes/login.css">

    <script>

        //Show button
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
        <form class="login-form" action="login_proc.php" method="POST">
            <h2>Login</h2>
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