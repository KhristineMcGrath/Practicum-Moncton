<?php
session_start();
include("connect.php");

if (isset($_POST["otp"])) {
	$errors = [];

	$email = $_SESSION['reset_email'];

	$sql = "SELECT `Code` from `employee` where `Email` = ?";

	$stmt = $con->prepare($sql);
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$stmt->store_result();


	if ($stmt->num_rows === 1) {
		$stmt->bind_result($storedOTP);
		$stmt->fetch();
		// Code that check the OTP

		// Combine the submitted OTP values into a single string
		if (isset($_POST['otp']) && is_array($_POST['otp'])) {
			$submittedOTP = implode('', $_POST['otp']); // Combine array elements into a string

			if ($storedOTP === $submittedOTP) {

				$zero = "0"; //  set `Code` to "0"
				$nullValue = null; // A variable to represent NULL
				
				$updateSql = "UPDATE `employee` SET `Code` = ?, `CodeCreate` = ?, `CodeExpire` = ? WHERE `Email` = ?";
				$updateStmt = $con->prepare($updateSql);
				$updateStmt->bind_param("ssss", $zero, $nullValue, $nullValue, $email);
				$updateStmt->execute();
				
				$msg = "correct OTP";
				header("location:passwordChange.php?message=$msg");
				exit();
			} else {
				// Handle incorrect OTP
				$msg = "Invalid OTP. Please try again.";
				header("Location: insertCode.php?message=" . urlencode($msg));
				exit();
			}
		} else {
			// Handle incomplete OTP input
			$msg = "Please fill all OTP fields.";
			header("Location: insertCode.php?message=" . urlencode($msg));
			exit();
		}
	} else {
		header("location:insertCode.php?message=" . urlencode("Invalid OTP. Please try again."));
		exit();
	}
} else {
	header("location:Login.php?message=" . urlencode("Cannot access this page."));
	exit();
}

?>