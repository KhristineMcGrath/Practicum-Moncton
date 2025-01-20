<?php
// Connection
include('connect.php');

// This is for the date picker selection.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedDate = $_POST['date'];
    echo "You selected: " . htmlspecialchars($selectedDate);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Visit Report Form</title>
    <link rel="stylesheet" href="includes/weeklyform.css"> <!-- Link to the CSS file -->
</head>

<body>
    <div class="container">
        <h1>Weekly Visit Report Form</h1>
        <form action="weeklyForm_proc.php" method="POST"> <!-- linked to proc.php -->

            <!-- Time section -->
            <div class="visit-schedule">
                <select name="day" required>
                    <!-- # Requirements state sunday - saturday will confirm if monday - sunday listing is OK. -->
                    <option value="" disabled selected>Select a day</option> <!-- Replaces placeholder html tag inside a dropdown. -->
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
                <!-- ## Switched from for loops to spinners as per requirements. Div's shorten the length of field in this area. -->
                <div>
                    <label for="hours">Select hours:</label>
                    <input type="number" id="hours" name="hours" min="0" max="12" step="1" required>
                </div>
                <!-- Minutes was not in the mock template, but was in the requirements. -->
                <div>
                    <label for="minutes">Select minutes:</label>
                    <input type="number" id="minutes" name="minutes" min="0" max="59" step="1" required>
                </div>
                <!--AM/PM selection --->
                <select name="ampm">
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
                <form method="post">
                    <label for="date">Select a date:
                        <input type="date" id="date" name="date" required></label>
                </form>
            </div>
            <br>
            <!-- The 5 pillars with dropdown sections-->
            <div class="pillars">
                <div>Pillar 1<br>Inclusion</div>
                <div>Pillar 2<br>Unique/Designed Services</div>
                <div>Pillar 3<br>Stability</div>
                <div>Pillar 4<br>Linking Connections</div>
                <div>Pillar 5<br>Increased Independence</div>
            </div>
            <div class="pillar-dropdowns">
                <select name="pillar1">
                    <option value="">Select an option</option>
                    <option value="Community Interaction">Community Interaction</option>
                    <option value="Volunteering">Volunteering</option>
                    <option value="Employment">Employment</option>
                    <option value="Others">Others</option>
                </select>
                <select name="pillar2">
                    <option value="">Select an option</option>
                </select>
                <select name="pillar3">
                    <option value="">Select an option</option>
                </select>
                <select name="pillar4">
                    <option value="">Select an option</option>
                </select>
                <select name="pillar5">
                    <option value="">Select an option</option>
                </select>
            </div>
            <br>
            <div class="notes-section">
                <textarea name="notes1" placeholder="Notes"></textarea>
                <textarea name="notes2" placeholder="Notes"></textarea>
                <textarea name="notes3" placeholder="Notes"></textarea>
                <textarea name="notes4" placeholder="Notes"></textarea>
                <textarea name="notes5" placeholder="Notes"></textarea>
            </div>
            <br>
            <div class="budget-section">
                <input type="text" name="income" placeholder="Income">
                <div class="expenses">
                    <input type="text" name="expense1" placeholder="Expense 1">
                    <input type="text" name="expense2" placeholder="Expense 2">
                    <input type="text" name="expense3" placeholder="Expense 3">
                </div>
            </div>
            <br>
            <textarea class="notes-box" name="general_notes" placeholder="General Notes"></textarea>
                    <!-- E-Sign section, looking into third party vendor to put here.  -->
            <div class="signature-section">
                <label for="staff_signature">Staff Signature:</label>
                <input type="text" id="staff_signature" name="staff_signature" placeholder="Enter your name">
            </div>
            <button type="submit">Submit Report</button>
        </form>
    </div>
</body>

</html>