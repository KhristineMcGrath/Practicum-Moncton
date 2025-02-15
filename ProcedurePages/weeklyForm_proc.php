<?php
include('../connect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize inputs
    $selectedDate = htmlspecialchars($_POST['date']);
    $day = htmlspecialchars($_POST['day']);
    $hours = isset($_POST['hours']) ? (int)$_POST['hours'] : null;
    $minutes = isset($_POST['minutes']) ? (int)$_POST['minutes'] : null;
    $ampm = htmlspecialchars($_POST['ampm']);

    // Validate inputs
    if (empty($selectedDate) || empty($day) || $hours === null || $minutes === null || empty($ampm)) {
        echo "All fields are required."; // Validation generic or specific msg's. ## REVIEW
        exit;
    }

    if ($hours < 0 || $hours > 12) {
        echo "Invalid hours value.";
        exit;
    }

    if ($minutes < 0 || $minutes > 59) {
        echo "Invalid minutes value.";
        exit;
    }

    // Display selected values (for debugging purposes)
    echo "You selected:<br>";
    echo "Date: $selectedDate<br>";
    echo "Day: $day<br>";
    echo "Time: $hours hour" . ($hours != 1 ? 's' : '') . " and $minutes minute" . ($minutes != 1 ? 's' : '') . " $ampm<br>";

    // SQL insert into the database
    $sql = "INSERT INTO weekly_visits (visit_date, day, hours, minutes, am_pm) VALUES (?, ?, ?, ?, ?)"; 
            //###  ? for prepared statement inserts. Might need to rewrite with more data.
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssiis", $selectedDate, $day, $hours, $minutes, $ampm);

        if ($stmt->execute()) {
            echo "Weekly visit report submitted successfully.";
        } else {
            echo "Error: Unable to submit the report. " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $con->error;
    }

    // Close!!
    $con->close();
}
?>