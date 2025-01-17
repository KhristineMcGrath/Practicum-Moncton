<?php
include ("connect.php");
// insert client data into db
function registerClient($firstName, $lastName, $email) {
  global $con;
  // this db name is optional atm, we can change it to whatever gets made
  $sql = "INSERT INTO clients (FirstName, LastName, Email) VALUES (?, ?, ?)";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("sss", $firstName, $lastName, $email);

  if ($stmt->execute()) {
    echo "New client registered successfully!";
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}

// post method for db
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $firstName = trim($_POST['first_name']);
  $lastName = trim($_POST['last_name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);

  // call function
  registerClient($firstName, $lastName, $email, $phone);
}
?>