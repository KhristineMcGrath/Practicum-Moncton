<?php
include("connect.php");

function generateRandomPassword($length = 12)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $password = '';
  for ($i = 0; $i < $length; $i++) {
    $password .= $characters[random_int(0, strlen($characters) - 1)];
  }
  return $password;
}

function isUserNameExist($userName)
{
  global $con;

  $sql = "SELECT 1 FROM employee WHERE UserName = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("s", $userName);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    return true; // Username exists
  }
  return false; // Username does not exist
}

function isEmailExist($email)
{
  global $con;

  $sql = "SELECT 1 FROM employee WHERE Email = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    return true; // Email exists
  }
  return false; // Email does not exist
}

function createEmployee($firstName, $lastName, $userName, $email, $role, $hashedPassword)
{
  global $con;

  // Prepare the SQL query to insert a new employee with Status set to 'Active'
  $sql = "INSERT INTO employee (FirstName, LastName, UserName, Email, Role, SetupPassword, SetStatus) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

  $status = 'Active';
  $stmt = $con->prepare($sql);
  $stmt->bind_param("sssssss", $firstName, $lastName, $userName, $email, $role, $hashedPassword, $status);

  if ($stmt->execute()) {
    echo "New employee created successfully.";
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}

?>