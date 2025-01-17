<?php 
include ("connect.php");
function createUser($firstName, $lastName, $email, $role) {
  global $con; // global conn

  $sql = "INSERT INTO employee (FirstName, LastName, Email, Role) VALUES (?, ?, ?, ?)";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("ssss", $firstName, $lastName, $email, $role);

  if ($stmt->execute()) {
    echo "New user created successfully";
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}

?>