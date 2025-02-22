<?php
session_start();
include('../connect.php');

if (!isset($_SESSION['reset_email'])) {
    header("location: ../Login.php?message=" . urlencode("Cannot access this page."));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["otp"])) {
    $email = $_SESSION['reset_email'];

    if (isset($_POST['otp']) && is_array($_POST['otp'])) {
        $submittedOTP = implode('', $_POST['otp']); // Combine OTP array into a string

        // Call the stored procedure
        $sql = "CALL VerifyOTP(?, ?)";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("ss", $email, $submittedOTP);
            $stmt->execute();

            // Get the result from the stored procedure
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $msg = $row['message'];

            if ($msg === "correct OTP") {
                header("location:../passwordChange.php?message=" . urlencode($msg));
            } else {
                header("location:../insertCode.php?message=" . urlencode($msg));
            }
            exit();
        } else {
            $msg = "Database error. Please try again.";
        }
    } else {
        $msg = "Please fill all OTP fields.";
    }

    header("Location: ../insertCode.php?message=" . urlencode($msg));
    exit();
} else {
    header("location:../Login.php?message=" . urlencode("Cannot access this page."));
    exit();
}
?>
