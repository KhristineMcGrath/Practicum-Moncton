<!DOCTYPE html>
<?php
include("connect.php");
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
        <!-- Logo section -->
        <div class="logo">
            <img src="includes/logo.png" alt="Logo">
        </div>

        <!-- Error message display -->
        <?php if (!empty($_GET['message'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

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
