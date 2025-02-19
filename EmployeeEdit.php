<?php
include("dbHandlers/handleUpdateEmployee.php");

$errorMessage = '';

// Check if the emp_id is passed in the URL
if (isset($_GET['emp_id']) && is_numeric($_GET['emp_id'])) {
    $employeeId = intval($_GET['emp_id']); // Sanitize input

    // Fetch the employee details based on emp_id
    $employee = getEmployeeById($employeeId);

    if (!$employee) {
        $errorMessage = 'Employee not found or invalid request.';
    }
} else {
    $errorMessage = 'Invalid request. Employee ID is missing.';
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errorMessage)) {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $userName = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($userName) || empty($email) || empty($role)) {
        $errorMessage = 'All fields are required.';
    } elseif (!in_array($role, ['Admin', 'Member', 'Supervisor'])) {
        $errorMessage = 'Invalid role selected.';
    } else {
        // Check if username or email already exists
        if (isUserNameExist($userName) && $userName !== $employee['Username']) {
            $errorMessage = 'The username is already taken.';
        } elseif (isEmailExist($email) && $email !== $employee['Email']) {
            $errorMessage = 'The email address is already registered.';
        } else {
            // Call the function to update the employee's information
            $updateSuccess = updateEmployee($employeeId, $firstName, $lastName, $userName, $email, $role);

            if ($updateSuccess) {
                // Redirect to success page
                header("Location: Success.php?FirstName=" . urlencode($firstName) .
                    "&LastName=" . urlencode($lastName) .
                    "&Email=" . urlencode($email) .
                    "&Role=" . urlencode($role) .
                    "&Username=" . urlencode($userName));
                exit();
            } else {
                $errorMessage = 'Failed to update employee. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="includes/EmployeeEdit.css">
</head>

<body>
    <div class="form-container">
        <h2>Edit Employee Account</h2><br>

        <!-- Display error message if there's an error -->
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>First Name: <input type="text" name="first_name"
                    value="<?php echo htmlspecialchars($employee['FirstName'] ?? ''); ?>" required></label><br>
            <label>Last Name: <input type="text" name="last_name"
                    value="<?php echo htmlspecialchars($employee['LastName'] ?? ''); ?>" required></label><br>
            <label>Username: <input type="text" name="user_name"
                    value="<?php echo htmlspecialchars($employee['Username'] ?? ''); ?>" required></label><br>
            <label>Email Address: <input type="email" name="email"
                    value="<?php echo htmlspecialchars($employee['Email'] ?? ''); ?>" required></label><br>
            <label>Role:
                <select name="role" required>
                    <option value="Member" <?php echo (isset($employee['Role']) && $employee['Role'] == 'Member') ? 'selected' : ''; ?>>Member</option>
                    <option value="Admin" <?php echo (isset($employee['Role']) && $employee['Role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="Supervisor" <?php echo (isset($employee['Role']) && $employee['Role'] == 'Supervisor') ? 'selected' : ''; ?>>Supervisor</option>
                </select>
            </label><br><br>

            <button type="submit" class="btn">Update Account</button>
        </form>
    </div>
</body>

</html>