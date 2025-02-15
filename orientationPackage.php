<?php
  //$currentTime = date('H:i'); just a line for later if we add the auto time insert to the form -jj
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orientation Package Form</title>
  <link rel="stylesheet" href="includes/orientationPackage.css">
  <script>
    function addShift() {
      const table = document.getElementById("shiftsTable").getElementsByTagName('tbody')[0];
      const newRow = table.insertRow();
      newRow.innerHTML = `
        <td><input type="date" name="shift_date[]"></td>
        <td><input type="time" name="shift_time[]"></td>
        <td><input type="text" name="shift_location[]"></td>
        <td><button type="button" class="removebtn" onclick="removeShift(this)">Remove</button></td>
      `;
    }

    // remove shift functionality
    function removeShift(button) {
      const row = button.parentNode.parentNode;
      row.parentNode.removeChild(row);
    }
    //add task functionality
    function addTask() {
      const table = document.getElementById("tasksTable").getElementsByTagName('tbody')[0];
      const newRow = table.insertRow();
      newRow.innerHTML = `
        <td><input type="text" name="task_description[]"></td>
        <td><input type="checkbox" name="task_completed[]"></td>
        <td><button type="button" class="removebtn" onclick="removeTask(this)">Remove</button></td>
      `;
    }

    function removeTask(button) {
      const row = button.parentNode.parentNode;
      row.parentNode.removeChild(row);
    }
  </script>
</head>

<body>
  <div class="container">
    <div class= "back-button-container">
    <input type="submit" value="Back" class="backbtn" onclick="window.location.href='AdminDash.php'">
  </div>
    <h1>Orientation Package Form</h1>
    <hr class="section-divider">
    <form name="orientationPackage" method="POST" action="orientationPackage_proc.php">
      <div class="form-row">
        <div class="form-group">
          <label>Staff First Name: <input type="text" name="staff_first_name" required></label>
          <label>Staff Last Name: <input type="text" name="staff_last_name" required></label>
        </div>
        <div class="form-group center">
          <div class="form-house">
            <label>Choose House:
              <select name="house" required>
                <option value="">Select a house</option>
                <option value="House 1">House 1</option>
                <option value="House 2">House 2</option>
                <option value="House 3">House 3</option>
              </select>
            </label>
          </div>
          <div class="form-category">
            <label>Category:
              <select name="category" required>
                <option value="">Select a category</option>
                <option value="Category 1">Category 1</option>
                <option value="Category 2">Category 2</option>
                <option value="Category 3">Category 3</option>
              </select>
            </label>
          </div>
        </div>
        <div class="form-group-supervisor">
          <label>Supervisor First Name: <input type="text" name="supervisor_first_name" required></label>
          <label>Supervisor Last Name: <input type="text" name="supervisor_last_name" required></label>
        </div>
      </div>

      <div class="form-row-date">
        <label>Date: <input type="date" name="orientationform_date[]"></label>
        <div class="form-row-time">
        <label>Time: <input type="time" name="orientationform_time[]" style="width: 100px;"></label>
  </div>
      </div>

      <h2>Shifts</h2>
      <table id="shiftsTable">
        <thead>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Location</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody>
          <!-- any shifts will move here -->
        </tbody>
      </table>
      <button type="button" class="addbtn" onclick="addShift()">Add Shift</button>

      <h2>Tasks</h2>
      <table id="tasksTable">
        <thead>
          <tr>
            <th>Task Description</th>
            <th>Completed</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody>
          <!-- any tasks will move here -->
        </tbody>
      </table>
      <button type="button" class="addbtn" onclick="addTask()">Add Task</button>

      <div class="form-row">
        <label>Feedback: <textarea name="feedback" class= "notes-textarea" rows="4" cols="50"></textarea></label>
      </div>

      <div class="form-row">
        <label>Staff Signature: <input type="text" name="staff_signature" placeholder="Sign here"></label>
      </div>

      <div class="form-row-btns">
        <button type="button" class="btn">Print</button>
        <button type="button" class="btn">Save</button>
        <button type="submit" class="btn">Submit</button>
      </div>
    </form>
  </div>
</body>

</html>