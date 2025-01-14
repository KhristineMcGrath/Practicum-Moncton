<?php
include("connect.php");

//POST METHOD - collects data for the DB
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $firstName = trim($_POST['firstname']);
  $lastName = trim($_POST['lastname']);
  $email = trim($_POST['email']);
  $role = trim($_POST['role']);
}
// // Validate email domain
// if (!preg_match('/@mcrinc\.net$/', $email)) {
//   die('Error: Only company email addresses (@mcrinc.net) are allowed.');
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Admin Page</title>
  <link rel="stylesheet" href="includes/admin.css">
</head>

<body>
  <div class="login-container">
    <h2>Create User Account</h2><br>
    <form method="POST" action=""> <!-- add action once function created -->
      <label>First Name: <input type="text" name="first_name" required></label><br>
      <label>Last Name: <input type="text" name="last_name" required></label><br>
      <label>Email Address: <input type="email" name="email" placeholder="@mcrinc.net" required></label><br>
      <label>Role:
        <select name="role" required> <!--dropdown option - come back to confirm staff as a selection item -->
          <option value="">Select a role</option>
          <option value="Staff">Staff</option>
          <option value="Admin">Admin</option>
          <option value="Supervisor">Supervisor</option>
        </select><br>
      </label><br>
      <button type="submit" class="btn">Create Account</button>
    </form>
  </div>
</body>

</html>