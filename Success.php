<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/vendor/autoload.php';


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

    <?php
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'patelmihir2605@gmail.com';            //SMTP username
      $mail->Password = 'nainzhgmaocbrptj';  // Use environment variables
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = 465;

      $mail->setFrom('patelmihir2605@gmail.com', 'Moncton Homes');
      $mail->addAddress($email, 'User');
      $mail->isHTML(true);
      $mail->Subject = 'Account Created Successfully';
      $mail->Body = "<p>Dear $firstName $lastName,</p>
                               <p>Your account has been successfully created.</p>
                               <p><strong>Username:</strong> $username</p>
                               <p><strong>Email:</strong> $email</p>
                               <p><strong>Role:</strong> $role</p>
                               <p><strong>Temporary Password:</strong> $password</p>
                               <p>Please log in and update your password.</p>
                               <p>Best regards,<br>Company Admin</p>";

      $mail->send();
    } catch (Exception $e) {
      $errorMessage = "Email could not be sent. Mailer Error: " . $mail->ErrorInfo;
    }

    ?>
    <h3> Email has been sent </h3>
    <!-- <p><strong>First Name:</strong> <?php //echo htmlspecialchars($firstName); ?></p>
    <p><strong>Last Name:</strong> <?php //echo htmlspecialchars($lastName); ?></p>
    <p><strong>Username:</strong> <?php //echo htmlspecialchars($username); ?></p>
    <p><strong>Email:</strong> <?php //echo htmlspecialchars($email); ?></p>
    <p><strong>Role:</strong> <?php //echo htmlspecialchars($role); ?></p>

    <?php
    // Only show the password field if a password was passed (for new employees)
    // if (!empty($password)) {
    //     echo '<p><strong>Generated Password:</strong> ' . htmlspecialchars($password) . '</p>';
    // }
    ?> -->

    <a href="adminConfigure.php" class="btn">Go to Admin Configuration</a>
  </div>
</body>

</html>