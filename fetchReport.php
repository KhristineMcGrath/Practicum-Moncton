<?php
include("connect.php");

$quarter = isset($_GET['quarter']) ? intval($_GET['quarter']) : 1;
$year = isset($_GET['year']) ? intval($_GET['year']) : date(format: "Y");

// Define months for each quarter
$months = [
    1 => [1, 2, 3],  // Q1
    2 => [4, 5, 6],  // Q2
    3 => [7, 8, 9],  // Q3
    4 => [10, 11, 12] // Q4
];

list($month1, $month2, $month3) = $months[$quarter];

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Query for Client Names
$sqlClients = "SELECT Client_ID, CONCAT(FirstName, ' ', LastName) AS FullName FROM Client WHERE Client_ID IN (SELECT Client_ID FROM Conflict)";
$resultClients = mysqli_query($con, $sqlClients);

// Query for Incidents Count
$sqlMonth1 = "SELECT Client_ID, COUNT(*) AS Month1 FROM Conflict WHERE MONTH(Date) = $month1 AND YEAR(Date) = $year GROUP BY Client_ID";
$sqlMonth2 = "SELECT Client_ID, COUNT(*) AS Month2 FROM Conflict WHERE MONTH(Date) = $month2 AND YEAR(Date) = $year GROUP BY Client_ID";
$sqlMonth3 = "SELECT Client_ID, COUNT(*) AS Month3 FROM Conflict WHERE MONTH(Date) = $month3 AND YEAR(Date) = $year GROUP BY Client_ID";

// Store results in associative arrays
$month1Data = [];
$month2Data = [];
$month3Data = [];

$resultMonth1 = mysqli_query($con, $sqlMonth1);
$resultMonth2 = mysqli_query($con, $sqlMonth2);
$resultMonth3 = mysqli_query($con, $sqlMonth3);

while ($row = mysqli_fetch_assoc($resultMonth1)) {
    $month1Data[$row['Client_ID']] = $row['Month1'];
}
while ($row = mysqli_fetch_assoc($resultMonth2)) {
    $month2Data[$row['Client_ID']] = $row['Month2'];
}
while ($row = mysqli_fetch_assoc($resultMonth3)) {
    $month3Data[$row['Client_ID']] = $row['Month3'];
}

// Generate the table rows dynamically
if ($resultClients->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($resultClients)) {
        $clientID = $row['Client_ID'];
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['FullName']) . "</td>";
        echo "<td>" . (isset($month1Data[$clientID]) ? $month1Data[$clientID] : 0) . "</td>";
        echo "<td>" . (isset($month2Data[$clientID]) ? $month2Data[$clientID] : 0) . "</td>";
        echo "<td>" . (isset($month3Data[$clientID]) ? $month3Data[$clientID] : 0) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No data found</td></tr>";
}
?>
