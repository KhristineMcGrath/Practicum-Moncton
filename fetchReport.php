<?php 
include("connect.php");

$quarter = isset($_GET['quarter']) ? intval($_GET['quarter']) : 1;
$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10; // Number of rows per page
$offset = ($page - 1) * $limit;

if ($con->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Fetch total number of clients using stored procedure
$totalQuery = $con->prepare("CALL GetTotalClients(?, ?, @total)");
$totalQuery->bind_param("ii", $quarter, $year);
$totalQuery->execute();
$totalQuery->close();

// Retrieve the output parameter
$totalResult = $con->query("SELECT @total AS total");
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);

// Fetch paginated clients using stored procedure
$clientsQuery = $con->prepare("CALL GetPaginatedClients(?, ?, ?, ?)");
$clientsQuery->bind_param("iiii", $quarter, $year, $limit, $offset);
$clientsQuery->execute();
$resultClients = $clientsQuery->get_result();

// Fetch incident counts for each month using stored procedure
$monthCounts = [];
$months = [1 => [1, 2, 3], 2 => [4, 5, 6], 3 => [7, 8, 9], 4 => [10, 11, 12]];
list($month1, $month2, $month3) = $months[$quarter];

$monthQueries = [$month1, $month2, $month3];

foreach ($monthQueries as $index => $monthVal) {
    $incidentQuery = $con->prepare("CALL GetIncidentCounts(?, ?)");
    $incidentQuery->bind_param("ii", $monthVal, $year);
    $incidentQuery->execute();
    $resultIncident = $incidentQuery->get_result();

    $monthCounts[$index] = [];
    while ($row = $resultIncident->fetch_assoc()) {
        $monthCounts[$index][$row['Client_ID']] = $row['Count'];
    }
    $incidentQuery->close();
}

// Generate table rows
$tableData = "";
if ($resultClients->num_rows > 0) {
    while ($row = $resultClients->fetch_assoc()) {
        $clientID = $row['Client_ID'];
        $tableData .= "<tr>";
        $tableData .= "<td>" . htmlspecialchars($row['FullName']) . "</td>";
        $tableData .= "<td>" . ($monthCounts[0][$clientID] ?? 0) . "</td>";
        $tableData .= "<td>" . ($monthCounts[1][$clientID] ?? 0) . "</td>";
        $tableData .= "<td>" . ($monthCounts[2][$clientID] ?? 0) . "</td>";
        $tableData .= "</tr>";
    }
} else {
    $tableData .= "<tr><td colspan='4'>No data found</td></tr>";
}

// Send JSON response
echo json_encode(["tableData" => $tableData, "totalPages" => $totalPages]);

?>