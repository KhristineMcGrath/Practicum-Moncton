<!DOCTYPE html>
<?php
include("connect.php");
include("Alert.php");
?>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quarterly Report</title>
    <link rel="stylesheet" href="includes/quarterlyForm.css">
</head>
<script>
    function updateReport() {
        let quarter = document.getElementById("quarterSelect").value;
        let year = document.getElementById("yearSelect").value;
        
        document.getElementById("reportTitle").innerText = `Quarterly Incidents Report - Q${quarter} ${year}`;

        let xhr = new XMLHttpRequest();
        xhr.open("GET", `fetchReport.php?quarter=${quarter}&year=${year}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("reportTable").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Call the function on page load with default values for Q1 and 2025
    window.onload = function() {
        updateReport();
    };
</script>


<body>
    <div class="container">
        <button class="back-button" onclick="window.history.back()"><- Back</button>
        <header>
            <img src="includes/Logo.png" alt="MCRI Logo" class="logo">
            <h1 id="reportTitle">Quarterly Incidents Report</h1>
        </header>

        <div class="filters">
            <label for="quarterSelect">Select a Quarter:</label>
            <select id="quarterSelect" onchange="updateReport()">
                
                <option value="1">Q1</option>
                <option value="2">Q2</option>
                <option value="3">Q3</option>
                <option value="4">Q4</option>
            </select>

            <label for="yearSelect">Select a Year:</label>
            <select id="yearSelect" onchange="updateReport()">
                <script>
                    let yearDropdown = document.getElementById("yearSelect");
                    let currentYear = new Date().getFullYear();
                    for (let i = currentYear; i >= currentYear - 10; i--) {
                        let option = document.createElement("option");
                        option.value = i;
                        option.text = i;
                        yearDropdown.add(option);
                    }
                </script>
            </select>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Month 1</th>
                    <th>Month 2</th>
                    <th>Month 3</th>
                </tr>
            </thead>
            <tbody id="reportTable">
                <tr><td colspan="4">Select a quarter and year to load data</td></tr>
            </tbody>
        </table>

        <div class="export-buttons">
            <button>Export to PDF</button>
            <button>Print</button>
        </div>
    </div>
</body>
</html>
