<?php
include 'connect.php';
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // make sure weekday is not empty
    if (empty($_POST["weekday"])) {
        $errors[] = "Weekday is required.";
    }

    // make sure date is not empty
    if (empty($_POST["date"])) { 
        $errors[] = "Date is required.";
    }

    // client name validation
    if (empty($_POST["client_first_name"])) { 
        $errors[] = "Client first name is required.";
    }

    if (empty($_POST["client_last_name"])) { 
        $errors[] = "Client last name is required.";
    }

    // staff name validation
    if (empty($_POST["staff_first_name"])) { 
        $errors[] = "Staff first name is required.";
    }

    if (empty($_POST["staff_last_name"])) {
        $errors[] = "Staff last name is required.";
    }

    // task empty error handling
    if (empty($_POST["tasks"])) {
        $errors[] = "At least one task is required.";
    }

    // esignature validation
    if (empty($_POST["e_signature"])) { 
        $errors[] = "E-signature is required.";
    }

    // if no errors process it
    if (empty($errors)) {
        echo "Weekend Form submitted successfully!";
    }
    // error handling for empty form 
    else {
        echo "Please fill out all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weekend Visit Report Form</title>
  <link rel="stylesheet" href="includes/weekendform.css">
  <script>
    function addRow() {
      const table = document.getElementById("tasksTable").getElementsByTagName('tbody')[0];
      const newRow = table.insertRow();
      newRow.innerHTML = `
        <td><input type="time" name="time[]" required></td>
        <td><input type="text" name="tasks[]" required></td>
        <td>
          <input type="checkbox" name="completed[]" value="1"> Yes
        </td>
        <td><button type="button" class="removebtn" onclick="removeRow(this)">Remove</button></td>
      `;
    }

    function removeRow(button) {
      const row = button.parentNode.parentNode;
      row.parentNode.removeChild(row);
    }
  </script>
</head>

<body>
  <div class="weekendform-container">
    <h1>Weekend Visit Report Form</h1>
    <?php if (!empty($errors)): ?>
      <div class="error-messages">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <form method="POST" action="ProcedurePages/weekendForm_proc.php">
      <div class="form-row">
        <div class="date-section">
          <label>Weekday:
            <select name="weekday" required>
              <option value="">Select a weekday</option>
              <option value="Saturday">Saturday</option>
              <option value="Sunday">Sunday</option>
            </select>
          </label>
          <label>Date: <input type="date" name="date" required></label>
        </div>

        <div class="names-section">
          <label>Client First Name: <input type="text" name="client_first_name" required></label>
          <label>Client Last Name: <input type="text" name="client_last_name" required></label>
          <label>Staff First Name: <input type="text" name="staff_first_name" required></label>
          <label>Staff Last Name: <input type="text" name="staff_last_name" required></label>
        </div>
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
        </tbody>
      </table>
      <button type="button" class="rowbtn" onclick="addRow()">Add Row</button>

      <div class="notes-section">
        <label>Notes: <textarea name="notes" rows="4" cols="50" class="notes-textarea"></textarea></label>
      </div>

      <div class="signature-section">
        <label>E-Signature: <input type="text" name="e_signature" placeholder="Sign here" required></label>
        <div>
          <button type="button" class="exprintbtn">Print</button>
          <button type="submit" class="exprintbtn">Export</button> <!-- temporary submit button -->
        </div>
      </div>
    </form>
  </div>
</body>

</html>
