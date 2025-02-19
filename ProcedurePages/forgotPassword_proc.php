<?php
session_start();
date_default_timezone_set('America/Halifax');
include('../connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../PHPMailer/vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["EmailCode"])) {
    $errors = [];

    // Validate Email
    $email = trim($_POST["EmailCode"]);
    if (empty($email)) {
        $errors[] = "Please enter your email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        // Check if email exists
        $sql = "SELECT `Email` FROM `employee` WHERE `Email` = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            // Generate reset code
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $resetCode = '';
            for ($i = 0; $i < 6; $i++) {
                $resetCode .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $codeCreated = date('Y-m-d H:i:s');
            $codeExpires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

            // Update database with the reset code
            $updateSql = "UPDATE `employee` SET `Code` = ?, `CodeCreate` = ?, `CodeExpire` = ? WHERE `Email` = ?";
            $updateStmt = $con->prepare($updateSql);
            $updateStmt->bind_param("ssss", $resetCode, $codeCreated, $codeExpires, $email);

            if ($updateStmt->execute()) {
                // Send reset email

                $_SESSION['reset_email'] = $email;
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
                    $mail->Subject = 'Password Reset Code';
                    $mail->Body = "Your password reset code is: <b>$resetCode</b><br>This code is valid for 30 minutes.";

                    $mail->send();
                    header("location:../InsertCode.php?message=" . urlencode("Check your email for the OTP."));
                    exit();
                } catch (Exception $e) {
                    $errors[] = "Could not send the email. Please try again.";
                }
            } else {
                $errors[] = "Failed to update the reset code. Please try again.";
            }
        } else {
            $errors[] = "Email does not exist.";
        }
    }

    if (!empty($errors)) {
        header("location:../forgotPassword.php?message=" . urlencode(implode(" ", $errors)));
        exit();
    }
} else {
    header("location:../Login.php?message=" . urlencode("Cannot access this page directly."));
    exit();
}
?>
