<?php
include("connect.php");
include("Alert.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

// Set the default time zone
date_default_timezone_set('America/Halifax');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["Email"])) {
    $errors = [];

    // Validate Email
    if (empty($_POST["Email"])) {
        $errors[] = "Please enter your Email.";
    } elseif (!filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($con, $_POST["Email"]);

        // Check if email exists in the database
        $sql = "SELECT `Email` FROM `employee` WHERE `Email` = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Generate reset code and expiration time
            
           //$resetCode = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6)); // 6-character random uppercase code
            
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $resetCode = '';
            for ($i = 0; $i < 6; $i++) {
                $resetCode .= $characters[random_int(0, strlen($characters) - 1)];
            }
            
            $codeCreated = date('Y-m-d H:i:s'); // Current time
            $codeExpires = date('Y-m-d H:i:s', strtotime('+30 minutes')); // 30 minutes from now

            // Update database with the reset code and timestamps
            $updateSql = "UPDATE `employee` 
                          SET `Code` = ?, `CodeCreate` = ?, `CodeExpire` = ? 
                          WHERE `Email` = ?";
            $updateStmt = $con->prepare($updateSql);
            $updateStmt->bind_param("ssss", $resetCode, $codeCreated, $codeExpires, $email);

            if ($updateStmt->execute()) {
                // Send reset code email

                try {
                    //Server settings
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth = true;                                   //Enable SMTP authentication
                    $mail->Username = 'patelmihir2605@gmail.com';            //SMTP username
                    $mail->Password = 'nainzhgmaocbrptj';                         //SMTP password 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients    
                    /*
                    *
                    *
                    *
                    * PLEASE CHANGE THIS LINE $mail->addAddress( 'mihirpatel2629@gmail.com', 'Admin');  BEFORE SENDING 
                    *
                    *
                    *
                    */
                    $mail->setFrom('fefone5701@kvegg.com', 'Moncton Community Center');
                    $mail->addAddress( 'mihirpatel2629@gmail.com', 'Admin');     //Add a recipient
                    $mail->IsHTML(true); // If you're using HTML content


                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Password Reset Code';
                    $mail->Body = "Your password reset code is: <b>$resetCode</b>
                                   
                                   This code is valid for 30 minutes.";

                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    $mail->send();

                    // $msg = "Your password reset code is: $resetCode. \n \n This code is valid for 30 minutes.";
                    // mail("mihirpatel2629@gmail.com", "Password Reset Code", wordwrap($msg, 70));

                    // Redirect to InsertCode.php with success message
                    $successMsg = "Check your email for the OTP." . $resetCode;
                    header("location:InsertCode.php?message=" . urlencode($successMsg));
                    exit();
                } catch (Exception $e) {
                    $errors[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $errors[] = "Failed to update the reset code. Please try again.";
            }
        } else {
            $errors[] = "Email does not exist.";
        }
    }

    if (!empty($errors)) {
        // Redirect back with error messages
        $msg = implode(" ", $errors); // Combine all errors into one message
        header("location:forgotPassword.php?message=" . urlencode($msg));
        exit();
    }
} else {
    // If accessed directly or without POST
    $msg = "Cannot access this page directly.";
    header("location:Login.php?message=" . urlencode($msg));
    exit();
}
?>