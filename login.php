<!DOCTYPE html>
<?php
include("connect.php");
include("Alert.php");
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCRI Login</title>
    <link rel="stylesheet" href="includes/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Favicon (tab icon) -->
    <link rel="icon" href="includes/logo.png" type="image/png">

    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility() {
            var password = document.getElementById("password");
            var icon = document.getElementById("password-icon");
            if (password.type === "password") {
                password.type = "text";
                icon.classList.remove("bx-show");
                icon.classList.add("bx-hide");
            } else {
                password.type = "password";
                icon.classList.remove("bx-hide");
                icon.classList.add("bx-show");
            }
        }
    </script>

</head>

<body>
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo">
            <img src="includes/logo.png" alt="Logo">
        </div>
        <h1>MCRI Login</h1>

        <!-- Error message display -->
        <?php if (!empty($message)): ?>
            <div class="error-message <?php echo $type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Form with POST method to submit login -->
        <form class="login-form" action="login_proc.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <button type="button" id="show-password" class="show-password" onclick="togglePasswordVisibility()">
                        <i id="password-icon" class='bx bx-hide'></i>
                    </button>
                </div>
            </div>
            <div class="password-helper">
                Can't remember your password? <a href="forgotPassword.php">Click here</a>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>

</html>