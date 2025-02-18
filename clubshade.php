<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Shade Report</title>
    <link rel="stylesheet" href="includes/clubshade.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body>
    <div class="container">
        <header>
            <img src="includes/Logo.png" alt="MCRI Logo" class="logo">
            <h1>Club Shade Report Form</h1>
        </header>

        <form id="report-form">
            <div class="form-layout">
                <div class="left-section">
                    <label for="date">Select a date:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="from-time">From:</label>
                    <input type="time" id="from-time" name="from-time" required>

                    <label for="to-time">To:</label>
                    <input type="time" id="to-time" name="to-time" required>

                    <label for="meal-served">Meal Served:</label>
                    <input type="text" id="meal-served" name="meal-served" required>
                </div>

                <div class="right-section">
                    <label for="counselor">Select a Counselor:</label>
                    <select id="counselor" name="counselor" required>
                        <option value="">Select a Counselor</option>
                        <option value="counselor1">Counselor 1</option>
                        <option value="counselor2">Counselor 2</option>
                    </select>

                    <label for="activities">Activities:</label>
                    <textarea id="activities" name="activities" required></textarea>
                </div>
            </div>

            <h3>Attendees:</h3>
            <div id="attendees">
                <div class="attendee">
                    <label>1.</label>
                    <input type="text" name="attendee_first_1" placeholder="First Name" required>
                    <input type="text" name="attendee_last_1" placeholder="Last Name" required>
                    <button type="button" class="remove-attendee">Remove</button>
                </div>
            </div>
            <button type="button" id="add-more">Add More</button>

            <label for="staff-signature">Staff Signature:</label>
            <input type="text" id="staff-signature" name="staff-signature" required>

            <div class="button-group">
                <button type="button" id="export-pdf">Export to PDF</button>
                <button type="button" id="format-pdf">Format to PDF</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("add-more").addEventListener("click", function () {
            const attendeesDiv = document.getElementById("attendees");
            const count = attendeesDiv.getElementsByClassName("attendee").length + 1;
            const newAttendee = document.createElement("div");
            newAttendee.classList.add("attendee");
            newAttendee.innerHTML = `
                <label>${count}.</label>
                <input type="text" name="attendee_first_${count}" placeholder="First Name" required>
                <input type="text" name="attendee_last_${count}" placeholder="Last Name" required>
                <button type="button" class="remove-attendee">Remove</button>
            `;
            attendeesDiv.appendChild(newAttendee);
        });

        document.getElementById("attendees").addEventListener("click", function (event) {
            if (event.target.classList.contains("remove-attendee")) {
                event.target.parentElement.remove();
                updateAttendeeNumbers();
            }
        });

        function updateAttendeeNumbers() {
            const attendees = document.getElementsByClassName("attendee");
            for (let i = 0; i < attendees.length; i++) {
                attendees[i].querySelector("label").textContent = `${i + 1}.`;
            }
        }

        function validateForm() {
            const fields = ['date', 'from-time', 'to-time', 'meal-served', 'counselor', 'activities', 'staff-signature'];
            let isValid = true;

            fields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value) {
                    input.style.border = "2px solid red";
                    isValid = false;
                } else {
                    input.style.border = "1px solid #ccc";
                }
            });

            const attendees = document.querySelectorAll("#attendees .attendee");
            attendees.forEach(attendee => {
                const firstName = attendee.querySelector("input[name^='attendee_first']");
                const lastName = attendee.querySelector("input[name^='attendee_last']");
                if (!firstName.value || !lastName.value) {
                    firstName.style.border = "2px solid red";
                    lastName.style.border = "2px solid red";
                    isValid = false;
                } else {
                    firstName.style.border = "1px solid #ccc";
                    lastName.style.border = "1px solid #ccc";
                }
            });

            return isValid;
        }

        document.getElementById("export-pdf").addEventListener("click", function () {
            if (!validateForm()) {
                alert("Please fill out all fields before exporting.");
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const img = new Image();
            img.src = "includes/Logo.png";
            img.onload = function () {
                doc.addImage(img, "PNG", 75, 10, 60, 20);
                generatePDF(doc, 'download');
            };

            img.onerror = function () {
                console.error("Error loading logo image. Ensure the path is correct.");
                generatePDF(doc, 'download');
            };
        });

        document.getElementById("format-pdf").addEventListener("click", function () {
            if (!validateForm()) {
                alert("Please fill out all fields before formatting.");
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const img = new Image();
            img.src = "includes/Logo.png";
            img.onload = function () {
                doc.addImage(img, "PNG", 75, 10, 60, 20);
                generatePDF(doc, 'open');
            };

            img.onerror = function () {
                console.error("Error loading logo image. Ensure the path is correct.");
                generatePDF(doc, 'open');
            };
        });

        function generatePDF(doc, action) {
            let y = 40;

            doc.setFont("helvetica", "bold");
            doc.setFontSize(18);
            doc.text("Club Shade Report", 20, y);
            y += 20;

            doc.setFont("helvetica", "normal");
            doc.setFontSize(12);

            function addText(label, text) {
                doc.setFont("helvetica", "bold");
                doc.text(label, 20, y);
                doc.setFont("helvetica", "normal");
                doc.text(text, 70, y);
                y += 12;
            }

            addText("Date:", document.getElementById("date").value);
            addText("From:", document.getElementById("from-time").value);
            addText("To:", document.getElementById("to-time").value);
            addText("Meal Served:", document.getElementById("meal-served").value);
            addText("Counselor:", document.getElementById("counselor").value);
            addText("Activities:", document.getElementById("activities").value);

            doc.setDrawColor(0);
            doc.setLineWidth(0.5);
            doc.line(20, y, 190, y);
            y += 15;

            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.text("Attendees:", 20, y);
            y += 10;

            const attendees = document.querySelectorAll("#attendees .attendee");
            doc.setFont("helvetica", "normal");
            const tableX = 20;
            const tableY = y;
            const rowHeight = 10;

            doc.setFont("helvetica", "bold");
            doc.text("No.", tableX, tableY);
            doc.text("First Name", tableX + 20, tableY);
            doc.text("Last Name", tableX + 80, tableY);
            y += rowHeight + 5;

            doc.setFont("helvetica", "normal");
            attendees.forEach((attendee, index) => {
                const firstName = attendee.querySelector("input[name^='attendee_first']").value;
                const lastName = attendee.querySelector("input[name^='attendee_last']").value;

                doc.text((index + 1).toString(), tableX, y);
                doc.text(firstName, tableX + 20, y);
                doc.text(lastName, tableX + 80, y);

                y += rowHeight;
            });
            y += 15;

            doc.setDrawColor(0);
            doc.setLineWidth(0.5);
            doc.line(20, y, 190, y);
            y += 10;

            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.text("Staff Signature:", 20, y);
            doc.setFont("helvetica", "normal");
            doc.text(document.getElementById("staff-signature").value, 70, y);

            doc.setDrawColor(0);
            doc.setLineWidth(0.5);
            doc.line(70, y + 2, 140, y + 2);
            y += 30;

            doc.setFontSize(10);
            doc.setFont("helvetica", "italic");
            doc.text("Generated by Club Shade Report System", 20, y);

            if (action === 'download') {
                doc.save('ClubShadeReport.pdf');
            } else if (action === 'open') {
                window.open(doc.output("bloburl"), "_blank");
            }
        }
    </script>
</body>

</html>
