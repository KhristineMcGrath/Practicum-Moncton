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

include('../connect.php');

$sql = "
DROP PROCEDURE IF EXISTS InsertConflict;
CREATE PROCEDURE InsertConflict(
    IN p_Emp_ID INT,
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
        Emp_ID, Staff_FirstName, Staff_LastName, StartTime, EndTime, Date,
        Damage_Injury, Police_Involved, SocialWorker_Contacted, Why_SW_Contacted,
        Observation, Individual_Action, Consequences, Emotional_Ok, Support_Plan,
        Team_Lead, Signature, Safe_Option, Team_Ok, Changes
    ) VALUES (
        p_Emp_ID, p_Staff_FirstName, p_Staff_LastName, p_StartTime, p_EndTime, p_Date,
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

// WHEN doing multi stored procedures run this so that it will move on to the next result set.
while ($con->more_results()) {
    $con->next_result();
}


// ## Insert Conflict.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_Emp_ID = trim($_POST['Emp_ID'] ?? null);
    $p_Staff_FirstName = trim($_POST['Staff_FirstName'] ?? null);
    $p_Staff_LastName = trim($_POST['Staff_LastName'] ?? null);
    $p_StartTime = trim($_POST['StartTime'] ?? null);
    $p_EndTime = trim($_POST['EndTime'] ?? null);
    $p_Date = trim($_POST['Date'] ?? null);
    $p_Damage_Injury = trim($_POST['Damage_Injury'] ?? null);
    $p_Police_Involved = trim($_POST['Police_Involved'] ?? null);
    $p_SocialWorker_Contacted = trim($_POST['SocialWorker_Contacted'] ?? null);
    $p_Why_SW_Contacted = trim($_POST['Why_SW_Contacted'] ?? null);
    $p_Observation = trim($_POST['Observation'] ?? null);
    $p_Individual_Action = trim($_POST['Individual_Action'] ?? null);
    $p_Consequences = trim($_POST['Consequences'] ?? null);
    $p_Emotional_Ok = trim($_POST['Emotional_Ok'] ?? null);
    $p_Support_Plan = trim($_POST['Support_Plan'] ?? null);
    $p_Team_Lead = trim($_POST['Team_Lead'] ?? null);
    $p_Signature = trim($_POST['Signature'] ?? null);
    $p_Safe_Option = trim($_POST['Safe_Option'] ?? null);
    $p_Team_Ok = trim($_POST['Team_Ok'] ?? null);
    $p_Changes = trim($_POST['Changes'] ?? null);
    $p_Cost = trim($_POST['Cost'] ?? null);
    $p_Officer_FullName = trim($_POST['Officer_FullName'] ?? null);
    $Options = isset($_POST['Options']) ? (array)$_POST['Options'] : []; 
    //checks if it is array,
    // and typecasted to avoid it being seen as a string.
    $p_Support_txt = isset($_POST['Support_txt']) ? trim($_POST['Support_txt']) : '';


  }

// This is to grab Emp_ID from table prior to inserting. 
  $stmt = $con->prepare("SELECT Emp_ID FROM employee WHERE FirstName = ? 
  AND LastName = ?");
  $stmt->bind_param("ss", $p_Staff_FirstName, $p_Staff_LastName);
  $stmt->execute();
  $stmt->bind_result($p_Emp_ID);
  $stmt->fetch();
  $stmt->close();
  
  //Debugging.
  echo "Emp_ID Retrieved: " . $p_Emp_ID;
  
$stmt = $con->prepare("CALL InsertConflict(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("isssssssissssiiisiss",$p_Emp_ID, $p_Staff_FirstName, $p_Staff_LastName, $p_StartTime, $p_EndTime, $p_Date,
$p_Damage_Injury, $p_Police_Involved, $p_SocialWorker_Contacted, $p_Why_SW_Contacted,
$p_Observation, $p_Individual_Action, $p_Consequences, $p_Emotional_Ok, $p_Support_Plan,
$p_Team_Lead, $p_Signature, $p_Safe_Option, $p_Team_Ok, $p_Changes);

$stmt->execute();
$stmt->close();

// ## Insert Estimate.
// This is to grab Emp_ID from table prior to inserting. 
$p_Incident_ID = $con->insert_id;

$stmt = $con->prepare("SELECT Incident_ID FROM conflict WHERE Incident_ID = ?");
$stmt->bind_param("i", $p_Incident_ID);
$stmt->execute();
$stmt->bind_result($p_Incident_ID);
$stmt->fetch();
$stmt->close();

$result = $con->query("SELECT LAST_INSERT_ID() AS Incident_ID");
    $row = $result->fetch_assoc();
    $p_Incident_ID = $row['Incident_ID'];

    if ($p_Cost !== null) {
        $stmt = $con->prepare("CALL InsertEstimate(?, ?)");
        $stmt->bind_param("id", $p_Incident_ID, $p_Cost);
        if (!$stmt->execute()) {
            die("Error inserting estimate: " . $stmt->error);
        }
        $stmt->close();
    }
        echo "Incident_ID Retrieved: " . $p_Incident_ID . "<br>";

// ## Insert Officer.

if (!empty($p_Officer_FullName)) {
    $stmt = $con->prepare("CALL InsertOfficer(?, ?)");
    $stmt->bind_param("is", $p_Incident_ID, $p_Officer_FullName);
    
    if (!$stmt->execute()) {
        die("Error inserting officer: " . $stmt->error);
    }
    $stmt->close();
}
//Debug
echo "Officer Inserted: " . $p_Officer_FullName ."<br>";

// ## InsertOptions.
// Removes undefined var warning.
$p_Option1 = $p_Option2 = $p_Option3 = $p_Option4 = $p_Option5 = null;
    // Assign values 5
    for ($i = 0; $i < min(5, count($Options)); $i++) {
        ${"p_Option" . ($i + 1)} = trim($Options[$i]); // Trim to remove spaces
    }
// Debugging Output
echo "Options Found: ";
print_r($Options);

$stmt = $con->prepare("CALL InsertOption(?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $p_Incident_ID, $p_Option1, $p_Option2, $p_Option3, $p_Option4, $p_Option5);

    if (!$stmt->execute()) {
        die("Error inserting options: " . $stmt->error);
    }

$stmt->close();
// echo "Options Inserted Successfully!";
// echo "Incident_ID before InsertSupport: " . $p_Incident_ID;



// ## InsertSupport
// Removes undefined variable warning
// Insert Support if Support_txt is provided
if (!empty($p_Support_txt)) {
    $stmt = $con->prepare("CALL InsertSupport(?, ?)");
    $stmt->bind_param("is", $p_Incident_ID, $p_Support_txt);

    if (!$stmt->execute()) {
        die("Error inserting support: " . $stmt->error);
    }
    $stmt->close();
    echo "Support Inserted Successfully!";
}
echo "Support_txt Value: " . $p_Support_txt;

// Print out insert to debug.
echo "<pre>";
print_r($_POST);
echo "</pre>";

$con->close(); //close connection.
?>