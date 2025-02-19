<?php
// Retrieve data from the URL parameters
$firstName = isset($_GET['FirstName']) ? $_GET['FirstName'] : '';
$lastName = isset($_GET['LastName']) ? $_GET['LastName'] : '';
$email = isset($_GET['Email']) ? $_GET['Email'] : '';
$role = isset($_GET['Role']) ? $_GET['Role'] : '';
$username = isset($_GET['Username']) ? $_GET['Username'] : '';  // Make sure it matches 'Username'
$password = isset($_GET['Password']) ? $_GET['Password'] : '';  // Password passed for new employee
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Account Creation Success</title>
  <link rel="stylesheet" href="includes/Success.css">
</head>
<body>
  <div class="success-container">
    <h2>Employee Account Created Successfully</h2><br>
    <p><strong>First Name:</strong> <?php echo htmlspecialchars($firstName); ?></p>
    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($lastName); ?></p>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars($role); ?></p>

    <?php
    // Only show the password field if a password was passed (for new employees)
    if (!empty($password)) {
        echo '<p><strong>Generated Password:</strong> ' . htmlspecialchars($password) . '</p>';
    }
    ?>

    <a href="adminConfigure.php" class="btn">Go to Admin Configuration</a>
  </div>
</body>
</html>