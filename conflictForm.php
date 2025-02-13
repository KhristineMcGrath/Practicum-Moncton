<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conflict Report</title>
    <link rel="stylesheet" href="includes/conflictForm.css">
    <script>
       function addTextbox(containerId) {
            const container = document.getElementById(containerId);
            const newDiv = document.createElement("div"); // Wrapper div for each textbox + button
            
            newDiv.innerHTML = `
                <input type="text" name="textboxes[]" placeholder="Enter text" required>
                <button type="button" onclick="removeTextbox(this)">Remove</button>
            `;

            container.appendChild(newDiv);
        }

        function removeTextbox(button) {
            button.parentNode.remove(); // Remove the textbox div
        }
    </script>
</head>

<body>
    <form action="ProcedurePages/conflictForm_proc.php" method="POST"> <!--Post is needed to process the stored procedure. -->
        <div class="container">
            <!-- Top section should be split into 2 grids, possibly more? -->
            <h2>CONFLICT REPORT</h2>
            <div class="top-section">
                <div class="left-grid">
                    <div class="section">
                        <label>First Name</label>
                        <input type="text" name="first_name" placeholder="First Name">
                        <label>Last Name</label>
                        <input type="text" name="last_name" placeholder="Last Name">
                    </div>
                    <div class="section">
                        <label>Start of Conflict</label>
                        <input type="time" name="StartTime">
                        <label>Date</label>
                        <input type="date" name="Date">
                    </div>
                </div>
                <div class="right-grid">
                    <div class="section">
                        <label>Staff First Name</label>
                        <input type="text" name="Staff_FirstName" placeholder="Staff First Name">
                        <label>Staff Last Name</label>
                        <input type="text" name="Staff_LastName" placeholder="Staff Last Name">
                    </div>
                    <div class="section">
                        <label>End of Conflict</label> <!-- Look at length of conflict for time section. -->
                        <input type="time" name="EndTime">
                    </div>
                </div>
            </div>
            <br>
            <!-- Bottom section should be split into 6 grids, expanding the container width to 1200px 
             did not fix the setting of the grids. -->
            <div class="bottom-section">
                <div class="section">
                    <label>Injuries & Damages?</label>
                    <input type="text" name="Damage_Injury">
                </div>
                <div class="section">
                    <label>Estimated Cost of Damages?</label>
                    <input type="text" name="cost">
                </div>
                <button type="button" onclick="addTextbox('section1')">Add Row</button> <br>
                <div id="section1"></div><br>
                <div class="section">
                    <label>Police Involved?</label>
                    <div class="radio-group">
                        <input type="radio" name="Police_Involved" value="1"> Yes
                        <input type="radio" name="Police_Involved" value="0"> No
                    </div>
                </div>
                <div class="section">
                    <label>Officer Name(s):</label>
                    <input type="text" name="officer_name">
                </div>
                <button type="button" onclick="addTextbox('section2')">Add Row</button> <br>
                <div id="section2"></div><br>
                <div class="section">
                    <label>Social Worker Contacted?</label>
                    <div class="radio-group">
                        <input type="radio" name="SocialWorker_Contacted" value="1"> Yes
                        <input type="radio" name="SocialWorker_Contacted" value="0"> No
                    </div>
                </div>
                <div class="section">
                    <label>Social Worker Name</label>
                    <input type="text" name="social_worker_name">
                </div>
            </div>
            <button type="button" onclick="addTextbox('section3')">Add Row</button> <br>
                <div id="section3"></div><br>
            <br>
            <!-- Row section for across is fine for now -->
            <div class="row">
                <label for="Why_SW_Contacted">Why was the social worker contacted?</label>
                <textarea id="Why_SW_Contacted" name="Why_SW_Contacted" rows="2"></textarea>
            </div>
            <div class="row">
                <label for="Observation">What was observed:</label>
                <textarea id="Observation" name="Observation" rows="2"></textarea>
            </div>
            <div class="row">
                <label for="Individual_Action">Actions of upset person:</label>
                <textarea id="Individual_Action" name="Individual_Action" rows="2"></textarea>
            </div>

            <!-- ## MIN options offered should be two, but other sections to add more. -->
            <div class="row">
                <label for="options">Options offered:</label>
                <textarea id="options" name="options" rows="2"></textarea>
            </div>
            <button type="button" onclick="addTextbox('section4')">Add Row</button> <br>
                <div id="section4"></div>
            <div class="row">
                <label for="support">What was done to support the upset person?</label>
                <textarea id="support" name="support" rows="2"></textarea>
            </div>
            <button type="button" onclick="addTextbox('section5')">Add Row</button> <br>
            <div id="section5"></div><br>
            <div class="row">
                <label for="Consequences">Logical consequences:</label>
                <textarea id="Consequences" name="Consequences" rows="2"></textarea>
            </div>
            <br>
            <!-- Adjust spacing between radio buttons and label, add borders as an option? Look into color scheme.-->
            <div class="section">
                <label>Is everyone okay physically and emotionally?</label>
                <div class="radio-group">
                    <input type="radio" id="okay_yes" name="Emotional_Ok" value="1"> Yes
                    <input type="radio" id="okay_no" name="Emotional_Ok" value="0"> No
                </div>
            </div>
            <div class="section">
                <label>Did we follow the person's support plan?</label>
                <div class="radio-group">
                    <input type="radio" id="okay_yes" name="Support_Plan" value="1"> Yes
                    <input type="radio" id="okay_no" name="Support_Plan" value="0"> No
                </div>
            </div>
            <div class="section">
                <label>Was a Team Leader identified and did he/she direct and communicate to all?</label>
                <div class="radio-group">
                    <input type="radio" id="okay_yes" name="Team_Lead" value="1"> Yes
                    <input type="radio" id="okay_no" name="Team_Lead" value="0"> No
                </div>
            </div>
            <div class="section">
                <label>Did we choose the safest option?</label>
                <div class="radio-group">
                    <input type="radio" id="okay_yes" name="Safe_Option" value="1"> Yes
                    <input type="radio" id="okay_no" name="Safe_Option" value="0"> No
                </div>
            </div>
            <div class="row">
                <label for="Team_Ok">What did my team members do well:</label>
                <textarea id="Team_Ok" name="Team_Ok" rows="2"></textarea>
            </div>
            <div class="row">
                <label for="Changes">What changes would we like to see next time?</label>
                <textarea id="Changes" name="Changes" rows="2"></textarea>
            </div>
            <div class="row">
                <label for="signature">Signature:</label>
                <input type="text" id="Signature" name="Signature" required>
            </div>
            <div class="row">
                <button type="submit">Submit Report</button>
            </div>
        </div>
    </form>
</body>

</html>