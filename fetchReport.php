<?php 
include("connect.php");

$quarter = isset($_GET['quarter']) ? intval($_GET['quarter']) : 1;
$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10; // Number of rows per page
$offset = ($page - 1) * $limit;

// Define months for each quarter
$months = [
    1 => [1, 2, 3],  
    2 => [4, 5, 6],  
    3 => [7, 8, 9],  
    4 => [10, 11, 12] 
];

list($month1, $month2, $month3) = $months[$quarter];

if ($con->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Get total number of records
$totalQuery = "SELECT COUNT(DISTINCT c.Client_ID) as total FROM Client c 
               INNER JOIN Conflict cf ON c.Client_ID = cf.Client_ID
               WHERE MONTH(cf.Date) IN ($month1, $month2, $month3) AND YEAR(cf.Date) = $year";
$totalResult = mysqli_query($con, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalPages = ceil($totalRow['total'] / $limit);

// Fetch paginated data
$sqlClients = "SELECT DISTINCT c.Client_ID, CONCAT(c.FirstName, ' ', c.LastName) AS FullName
               FROM Client c
               INNER JOIN Conflict cf ON c.Client_ID = cf.Client_ID
               WHERE MONTH(cf.Date) IN ($month1, $month2, $month3)  
               AND YEAR(cf.Date) = $year 
               LIMIT $limit OFFSET $offset";
$resultClients = mysqli_query($con, $sqlClients);

// Fetch incident counts
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

// Generate table rows
$tableData = "";
if ($resultClients->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($resultClients)) {
        $clientID = $row['Client_ID'];
        $tableData .= "<tr>";
        $tableData .= "<td>" . htmlspecialchars($row['FullName']) . "</td>";
        $tableData .= "<td>" . (isset($month1Data[$clientID]) ? $month1Data[$clientID] : 0) . "</td>";
        $tableData .= "<td>" . (isset($month2Data[$clientID]) ? $month2Data[$clientID] : 0) . "</td>";
        $tableData .= "<td>" . (isset($month3Data[$clientID]) ? $month3Data[$clientID] : 0) . "</td>";
        $tableData .= "</tr>";
    }
} else {
    $tableData .= "<tr><td colspan='4'>No data found</td></tr>";
}

// Send JSON response
echo json_encode(["tableData" => $tableData, "totalPages" => $totalPages]);

?>