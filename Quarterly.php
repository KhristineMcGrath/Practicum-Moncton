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

<body>
    <div class="container">
        <button class="back-button" onclick="window.history.back()">⬅ Back</button>
        <header>
            <img src="includes/Logo.png" alt="MCRI Logo" class="logo">
            <h1 id="reportTitle">Quarterly Incidents Report</h1>
        </header>
        
        <div class="filters">
            <label for="quarterSelect">Select a Quarter:</label>
            <select id="quarterSelect" onchange="updateQuarter()">
                <option value="Q1">Q1</option>
                <option value="Q2">Q2</option>
                <option value="Q3">Q3</option>
                <option value="Q4">Q4</option>
            </select>
            <label for="yearSelect">Select a Year:</label>
            <select id="yearSelect" onchange="updateQuarter()">
                <script> 
                // this shows the year list 
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
            <tbody>
                <!-- Temp client added to check html -->
                <tr>
                    <td>Adam Smith</td>
                    <td><input type="text" value="0"></td>
                    <td><input type="text" value="1"></td>
                    <td><input type="text" value="0"></td>
                </tr>
                <tr>
                    <td>Jamie Massey</td>
                    <td><input type="text" value="2"></td>
                    <td><input type="text" value="0"></td>
                    <td><input type="text" value="1"></td>
                </tr>
                <!-- More rows -->
            </tbody>
        </table>
        
        <div class="controls">
            <label>Show: 
                <select>
                    <option>5</option>
                    <option>10</option>
                    <option>25</option>
                </select>
            </label>
            
            <label>Filter By: 
                <select>
                    <option>N/A</option>
                    <option>Most Incidents</option>
                    <option>Least Incidents</option>
                </select>
            </label>
            
            <button class="apply">Apply Filter</button>
        </div>
        
        <div class="export-buttons">
            <button>Export to PDF</button>
            <button>Print</button>
        </div>
    </div>

    <script>
        function updateQuarter() {
            let quarter = document.getElementById("quarterSelect").value;
            let year = document.getElementById("yearSelect").value;
            document.getElementById("reportTitle").innerText = `Quarterly Incidents Report - ${quarter} ${year}`;
        }
    </script>
</body>
</html>