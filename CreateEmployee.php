<?php
include("dbHandlers/handleEmployees.php");

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $firstName = trim($_POST['first_name']);
  $lastName = trim($_POST['last_name']);
  $userName = trim($_POST['user_name']);
  $email = trim($_POST['email']);
  $role = trim($_POST['role']);

  // Basic validation
  if (empty($firstName) || empty($lastName) || empty($userName) || empty($email) || empty($role)) {
    $errorMessage = 'All fields are required.';
  } else {
    // Check if the username or email already exists in the database
    if (isUserNameExist($userName)) {
      $errorMessage = 'The username is already taken.';
    } elseif (isEmailExist($email)) {
      $errorMessage = 'The email address is already registered.';
    } else {
      // Generate a random 12-digit password
      $password = generateRandomPassword(12);

      // Hash the password for storage
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Call the function to create the user with hashed password
      createEmployee($firstName, $lastName, $userName, $email, $role, $hashedPassword);

      // Redirect to Success.php with the user's information and the plain password
      // Redirect to Success page after successful creation with the password
      header("Location: Success.php?FirstName=" . urlencode($firstName) .
        "&LastName=" . urlencode($lastName) .
        "&Email=" . urlencode($email) .
        "&Role=" . urlencode($role) .
        "&Username=" . urlencode($userName) .   // Add the username to the URL
        "&Password=" . urlencode($password));
      exit();

    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Create Employee</title>
  <link rel="stylesheet" href="includes/CreateEmployee.css">
</head>

<body>
  <div class="form-container">
    <h2>Create Employee Account</h2><br>

    <!-- Display error message if there's an error -->
    <?php if (!empty($errorMessage)): ?>
      <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <label>First Name: <input type="text" name="first_name" required></label><br>
      <label>Last Name: <input type="text" name="last_name" required></label><br>
      <label>Username: <input type="text" name="user_name" required></label><br>
      <label>Email Address: <input type="email" name="email" placeholder="@mcrinc.net" required></label><br>
      <label>Role:
        <select name="role" required>
          <option value="">Select a role</option>
          <option value="Member">Member</option>
          <option value="Admin">Admin</option>
          <option value="Supervisor">Supervisor</option>
        </select><br>
      </label><br>
      <button type="submit" class="btn">Create Account</button>
    </form>
  </div>
</body>

</html>
