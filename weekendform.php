<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weekend Visit Report Form</title>
  <link rel="stylesheet" href="includes/weekendform.css">
  <script>
    // function that adds a new row to the tasks table
    function addRow() {
      const table = document.getElementById("tasksTable").getElementsByTagName('tbody')[0];
      const newRow = table.insertRow();
      newRow.innerHTML = `
        <td><input type="time" name="time[]" required></td>
        <td><input type="text" name="tasks[]" required></td>
        <td>
          <input type="checkbox" name="completed[]"> Yes
          <input type="checkbox" name="completed[]"> No
        </td>
        <td><button type="button" onclick="removeRow(this)">Remove</button></td>
      `;
    }

    function removeRow(button) {
      const row = button.parentNode.parentNode;
      row.parentNode.removeChild(row);
    }
  </script>
</head>

<body>
  <div class="container">
    <h1>Weekend Visit Report Form</h1>
    <form method="POST" action="">
      <div class="date-section">
        <label>Weekday:
          <select name="weekday" required>
            <option value="">Select a weekday</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
          </select>
        </label>
        <label>Date: <input type="date" name="date" required></label>
      </div>

      <div class="names-section">
        <label>Client First Name: <input type="text" name="client_first_name" required></label>
        <label>Client Last Name: <input type="text" name="client_last_name" required></label>
        <label><br>Staff First Name: <input type="text" name="staff_first_name" required></label>
        <label>Staff Last Name: <input type="text" name="staff_last_name" required></label>
      </div>

      <table id="tasksTable">
        <thead>
          <tr>
            <th>Time</th>
            <th>Tasks</th>
            <th>Completed</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="time" name="time[]" required></td>
            <td><input type="text" name="tasks[]" required></td>
            <td>
              <input type="checkbox" name="completed[]"> Yes
              <input type="checkbox" name="completed[]"> No
            </td>
            <td><button type="button" onclick="removeRow(this)">Remove</button></td>
          </tr>
        </tbody>
      </table>
      <button type="button" onclick="addRow()">Add Row</button>

      <div class="notes-section">
        <label>Notes: <textarea name="notes" rows="4" cols="50"></textarea></label>
      </div>

      <div class="signature-section">
        <label>E-Signature: <input type="text" name="e_signature" placeholder="Sign here" required></label>
        <div>
          <button type="button" class="btn">Print</button>
          <button type="button" class="btn">Export</button>
        </div>
      </div>
    </form>
  </div>
</body>

</html>