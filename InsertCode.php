<!DOCTYPE html>
<?php
include("connect.php");
include("Alert.php");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm OTP</title>
    <link rel="stylesheet" href="includes/confirm_otp.css">
</head>
<body>
    <div class="otp-container">
        <form action="ProcedurePages/InsertCodeProc.php" method="POST">
            <h2>Confirm OTP</h2>
            <p>Please enter the 6-digit OTP sent to your email address.</p>
            <div class="otp-inputs">
                <input type="text" maxlength="1" name="otp[]" class="otp-field" required>
                <input type="text" maxlength="1" name="otp[]" class="otp-field" required>
                <input type="text" maxlength="1" name="otp[]" class="otp-field" required>
                <input type="text" maxlength="1" name="otp[]" class="otp-field" required>
                <input type="text" maxlength="1" name="otp[]" class="otp-field" required>
                <input type="text" maxlength="1" name="otp[]" class="otp-field" required>
            </div>
            <button type="submit" class="submit-btn">Verify OTP</button>
        </form>
    </div>

    <script>
        // Automatically move focus to the next input field
        const otpFields = document.querySelectorAll('.otp-field');
        otpFields.forEach((field, index) => {
            field.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < otpFields.length - 1) {
                    otpFields[index + 1].focus();
                }
            });

            field.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpFields[index - 1].focus();
                }
            });
        });
    </script>
</body>
</html>

