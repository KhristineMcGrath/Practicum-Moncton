<?php
    // ### EXAMPLE of insert stored procedure. Delimiter allows multi-statements inside one block.
// p = is for the parameter
// TINYINT serves as a boolean between 0-1. 
// DELIMITER //

// CREATE PROCEDURE InsertExample()
// BEGIN
//     INSERT INTO my_table (column1) VALUES ('value1');
//     INSERT INTO my_table (column2) VALUES ('value2');
// END //

// DELIMITER ;

include('connect.php');

$sql = "
DROP PROCEDURE IF EXISTS InsertConflict;
CREATE PROCEDURE InsertConflict(
    IN p_Client_ID INT,
    IN p_Staff_FirstName VARCHAR(50),
    IN p_Staff_LastName VARCHAR(50),
    IN p_StartTime TIME,
    IN p_EndTime TIME,
    IN p_Date DATE,
    IN p_Damage_Injury VARCHAR(200),
    IN p_Police_Involved TINYINT(1), 
    IN p_SocialWorker_Contacted TINYINT(1),
    IN p_Why_SW_Contacted VARCHAR(500),
    IN p_Observation VARCHAR(500),
    IN p_Individual_Action VARCHAR(300),
    IN p_Consequences VARCHAR(300),
    IN p_Emotional_Ok TINYINT(1),
    IN p_Support_Plan TINYINT(1),
    IN p_Team_Lead TINYINT(1),
    IN p_Signature VARCHAR(100),
    IN p_Safe_Option TINYINT(1),
    IN p_Team_Ok VARCHAR(150),
    IN p_Changes VARCHAR(150)
)
BEGIN
    INSERT INTO conflict (
        Client_ID, Staff_FirstName, Staff_LastName, StartTime, EndTime, Date,
        Damage_Injury, Police_Involved, SocialWorker_Contacted, Why_SW_Contacted,
        Observation, Individual_Action, Consequences, Emotional_Ok, Support_Plan,
        Team_Lead, Signature, Safe_Option, Team_Ok, Changes
    ) VALUES (
        p_Client_ID, p_Staff_FirstName, p_Staff_LastName, p_StartTime, p_EndTime, p_Date,
        p_Damage_Injury, p_Police_Involved, p_SocialWorker_Contacted, p_Why_SW_Contacted,
        p_Observation, p_Individual_Action, p_Consequences, p_Emotional_Ok, p_Support_Plan,
        p_Team_Lead, p_Signature, p_Safe_Option, p_Team_Ok, p_Changes
    );
END;

DROP PROCEDURE IF EXISTS InsertEstimate;
CREATE PROCEDURE InsertEstimate(
    IN p_Incident_ID INT,
    IN p_Cost DOUBLE
)
BEGIN
    INSERT INTO estimate (Incident_ID, Cost)
    VALUES (p_Incident_ID, p_Cost);
END;

DROP PROCEDURE IF EXISTS InsertOfficer;
CREATE PROCEDURE InsertOfficer(
    IN p_Incident_ID INT,
    IN p_Officer_FullName VARCHAR(70)
)
BEGIN
    INSERT INTO officer (Incident_ID, Officer_FullName)
    VALUES (p_Incident_ID, p_Officer_FullName);
END;

DROP PROCEDURE IF EXISTS InsertOption;
CREATE PROCEDURE InsertOption(
    IN p_Incident_ID INT,
    IN p_Option1 VARCHAR(150),
    IN p_Option2 VARCHAR(150),
    IN p_Option3 VARCHAR(150),
    IN p_Option4 VARCHAR(150),
    IN p_Option5 VARCHAR(150)
)
BEGIN
    INSERT INTO `option` (Incident_ID, Option1, Option2, Option3, Option4, Option5)
    VALUES (p_Incident_ID, p_Option1, p_Option2, p_Option3, p_Option4, p_Option5);
END;

DROP PROCEDURE IF EXISTS InsertSupport;
CREATE PROCEDURE InsertSupport(
    IN p_Incident_ID INT,
    IN p_Support_txt VARCHAR(150)
)
BEGIN
    INSERT INTO support (Incident_ID, Support_txt)
    VALUES (p_Incident_ID, p_Support_txt);
END;

DROP PROCEDURE IF EXISTS InsertWorker;
CREATE PROCEDURE InsertWorker(
    IN p_Incident_ID INT,
    IN p_Worker_First_Name VARCHAR(50),
    IN p_Worker_Last_Name VARCHAR(50)
)
BEGIN
    INSERT INTO worker (Incident_ID, Worker_First_Name, Worker_Last_Name)
    VALUES (p_Incident_ID, p_Worker_First_Name, p_Worker_Last_Name);
END;
";

// You can use query with the delimiter or NO delimiter with multi_query.
if ($con->multi_query($sql)) {
    echo "Stored procedure created!";
} else {
    echo "Error: " . $con->error;
}

// $_POST NAME of the field....
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_Staff_FirstName = trim($_POST['Staff_FirstName']); // left matches parameters, right matches form name.
    $p_Staff_LastName = trim($_POST['Staff_LastName']);
    $p_StartTime = trim($_POST['StartTime']);
    $p_Date = trim($_POST['Date']);
    $p_Damage_Injury = trim($_POST['Damage_Injury']);
    $p_Police_Involved = trim($_POST['Police_Involved']);
    $p_SocialWorker_Contacted = trim($_POST['SocialWorker_Contacted']);
    $p_Why_SW_Contacted = trim($_POST['Why_SW_Contacted']);
    $p_Observation = trim($_POST['Observation']);
    $p_Individual_Action = trim($_POST['Individual_Action']);
    $p_Consequences = trim($_POST['Consequences']);
    $p_Emotional_Ok = trim($_POST['Emotional_Ok']);
    $p_Support_Plan = trim($_POST['Support_Plan']);
    $p_Team_Lead = trim($_POST['Team_Lead']);
    $p_Signature = trim($_POST['Signature']);
    $p_Safe_Option = trim($_POST['Safe_Option']);
    $p_Team_Ok = trim($_POST['Team_Ok']);
    $p_Changes = trim($_POST['Changes']);
  }


$stmt = $con->prepare("CALL InsertConflict(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bindParam("issiissiissssiiisiss",$p_Client_ID, $p_Staff_FirstName, $p_Staff_LastName, $p_StartTime, $p_EndTime, $p_Date,
$p_Damage_Injury, $p_Police_Involved, $p_SocialWorker_Contacted, $p_Why_SW_Contacted,
$p_Observation, $p_Individual_Action, $p_Consequences, $p_Emotional_Ok, $p_Support_Plan,
$p_Team_Lead, $p_Signature, $p_Safe_Option, $p_Team_Ok, $p_Changes );

$stmt->execute();

$stmt->close();


// Print out insert to debug.
echo "<pre>";
print_r($_POST);
echo "</pre>";


$con->close(); //close connection.

?>