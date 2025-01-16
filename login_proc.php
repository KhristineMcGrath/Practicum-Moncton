<?php
include("connect.php");
?>

<?php
if (isset($_POST["username"])) {

    $sql = "SELECT `Username`, `Password`, `Email`, `Status`, `role`
            FROM `employee`
            WHERE `Username` = '".$_POST["username"]."' 
            AND `Password` = '".$_POST["password"]."'";

    $result = mysqli_query($con, $sql);

    if (mysqli_affected_rows($con) == 1) {

        $rows = mysqli_fetch_assoc($result);
        $password = $rows["Password"];
        $status = $rows["Status"];  // Fetch the status from the database
        $role = $rows["role"];      // Fetch the role from the database

        if ($status == 'Inactive') {
            // Account is inactive, display the message and exit
            $msg = "Account currently not activated. Please contact your supervisor.";
            echo $msg;
            exit();
        }

        // Continue with role-based redirection if status is active
        if ($role == 'Admin') {
            header("location:AdminDash.php");  
        } elseif ($role == 'Member') {
            header("location:MemberDash.php");
        } elseif ($role == 'Supervisor') {
            header("location:SuperDash.php");  
        } else {
            $msg = "Invalid role. Access denied.";
            header("location:Login.php?message=$msg");
        }

        exit();

    } else {
        $msg = "Please check your username or password.";
        header("location:Login.php?message=$msg");
    }
} else {
    $msg = "CAN'T ACCESS THIS PAGE DIRECTLY";
    header("location:Login.php?message=$msg");
}
?>
