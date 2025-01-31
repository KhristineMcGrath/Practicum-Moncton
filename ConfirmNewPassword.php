<?php
session_start(); 

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: Login.php?message=" . urlencode("Invalid request. Please try again."));
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Confirm New Password</title>
    <link rel="stylesheet" href="includes/ConfirmPass.css">
</head>

<body>
    <div class="form-container">
        <h2>Set New Password</h2>
        <form method="POST" action="dbHandlers/handleConfirmNewPassword.php">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
            
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required minlength="6" maxlength="25">
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6" maxlength="25">
            
            <button type="submit" class="btn">Update Password</button>
        </form>
    </div>
</body>

</html>
