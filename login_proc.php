<?php
include("connect.php");
include("Alert.php");
?>

<?php
if (($_POST["username"])) {

    $sql = "SELECT `Username`, `Password`, `Email`, `Status` 
    FROM `employee` 
    WHERE `Username` = '".$_POST["username"]."' 
    AND `Password` = '".$_POST["password"]."'";

    $result = mysqli_query($con, $sql);

    if (mysqli_affected_rows($con) == 1) {

        $rows = mysqli_fetch_assoc($result);
        $password = $rows["Password"];


            $msg = "Sucessfully Loged in";
            header("location:index.php?message=$msg");  //redirect before evenr alreat box apprear

            exit();
            //echo $_SESSION["SESS_FIRST_NAME"];

    } else {
        $msg = "Please check your username";
        header("location:Login.php?message=$msg");
    }
} else {
    $msg = "CAN'T ACCESS THIS PAGE DIRECTLY";
    header("location:Login.php?message=$msg");
}
//redirect the user back to the form
//header("location:index.php?message=$msg");
?>