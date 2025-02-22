<?php
include("dbHandlers/handleLoadEmployee.php");
$employees = loadEmployees();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Configuration Page</title>
    <link rel="stylesheet" href="includes/adminConfig.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Toggle sorting of employees
        function toggleSort() {
            const urlParams = new URLSearchParams(window.location.search);
            const currentSort = urlParams.get('sort') || 'status';
            const newSort = currentSort === 'status' ? 'Emp_ID' : 'status';
            window.location.search = `?sort=${newSort}`;
        }

    </script>
</head>

<body>
    <header>
        <div class="logo">
            <img src="includes/Logo.png" alt="Logo">
        </div>
        <div class="title">
            <h1>Admin Configuration Page</h1>
        </div>
        <nav>
            <a href="AdminDash.php" class="nav-button">Dashboard</a>
        </nav>
    </header>

    <main>
        <section class="user-management">
            <div class="button-container">
                <button class="add-user-button"><a href="CreateEmployee.php">Add User</a></button>
                <button class="swap-button" onclick="toggleSort()">Swap</button>
            </div>
            <div class="user-list">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Generate</th>
                            <th>Temporary Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sorting logic based on URL parameter
                        if (isset($_GET['sort']) && $_GET['sort'] === 'status') {
                            usort($employees, function ($a, $b) {
                                if ($a['SetStatus'] === 'Active' && $b['SetStatus'] !== 'Active') {
                                    return -1;
                                } elseif ($a['SetStatus'] !== 'Active' && $b['SetStatus'] === 'Active') {
                                    return 1;
                                } else {
                                    return 0;
                                }
                            });
                        } else {
                            usort($employees, function ($a, $b) {
                                return $a['Emp_ID'] <=> $b['Emp_ID'];
                            });
                        }

                        ?>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?= htmlspecialchars($employee['Emp_ID']) ?></td>
                                <td><?= htmlspecialchars($employee['FirstName']) ?></td>
                                <td><?= htmlspecialchars($employee['LastName']) ?></td>
                                <td><?= htmlspecialchars($employee['Username']) ?></td>
                                <td><?= htmlspecialchars($employee['Email']) ?></td>
                                <td><?= htmlspecialchars($employee['SetStatus']) ?></td>
                                <td><?= htmlspecialchars($employee['Role']) ?></td>
                                <td>
                                    <button class="temp-pass-button"
                                        data-userid="<?= htmlspecialchars($employee['Emp_ID']) ?>">Generate</button>
                                </td>
                                <td>
                                    <?php if ($employee['TempPassword'] != '0'): ?>
                                        <span class="generated-code" data-userid="<?= htmlspecialchars($employee['Emp_ID']) ?>"
                                            data-initial="true">Password Encrypted</span>
                                    <?php else: ?>
                                        <span class="generated-code"
                                            data-userid="<?= htmlspecialchars($employee['Emp_ID']) ?>">No
                                            temporary password</span>
                                    <?php endif; ?>
                                    <button class="destroy-pass-button"
                                        data-userid="<?= htmlspecialchars($employee['Emp_ID']) ?>">Destroy</button>
                                </td>
                                <td>
                                    <button class="edit-button"
                                        onclick="window.location.href='EmployeeEdit.php?emp_id=<?= htmlspecialchars($employee['Emp_ID']) ?>'">Edit</button>

                                    <!-- Dynamic toggle button for status -->
                                    <button class="action-button toggle-status-button"
                                        data-userid="<?= htmlspecialchars($employee['Emp_ID']) ?>"
                                        data-status="<?= htmlspecialchars($employee['SetStatus']) ?>"
                                        style="background-color: <?= $employee['SetStatus'] === 'Active' ? 'red' : 'green' ?>;">
                                        <?= $employee['SetStatus'] === 'Active' ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>

<script>
    // Handle the status toggle button click
    $(".toggle-status-button").click(function () {
        var userId = $(this).data("userid");
        var currentStatus = $(this).data("status");
        var newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active'; // Updated status comparison

        $.ajax({
            url: "dbHandlers/handleLoadEmployee.php",
            method: "POST",
            data: {
                action: "toggleStatus",
                userId: userId,
                status: newStatus,  // Ensure the new status matches the capitalized format
            },
            success: function (response) {
                var res = JSON.parse(response);
                if (res.status === "success") {
                    var button = $(".toggle-status-button[data-userid='" + userId + "']");
                    button.text(newStatus === 'Active' ? "Deactivate" : "Activate")
                        .css("background-color", newStatus === 'Active' ? "red" : "green")
                        .data("status", newStatus);

                    var statusCell = button.closest('tr').find('td').eq(5);
                    statusCell.text(newStatus); // Display status with first letter capitalized
                } else {
                    alert(res.message || "Error updating status.");
                }
            },
            error: function () {
                alert("Error updating status. Please try again.");
            },
        });
    });



    // When the page loads, set the initial status of the toggle button based on the employee status
    $(document).ready(function () {
        $(".toggle-status-button").each(function () {
            var status = $(this).data("status");
            $(this).text(status === 'Active' ? "Deactivate" : "Activate")
                .css("background-color", status === 'Active' ? "red" : "green");
        });
    });

    // Handle the generate password button click
    $(".temp-pass-button").click(function () {
        var userId = $(this).data("userid");

        // Generate a random 12-character password
        var generatedPassword = Array(12)
            .fill(0)
            .map(() =>
                "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789".charAt(
                    Math.floor(Math.random() * 62)
                )
            )
            .join("");

        // Temporarily display the generated password
        $("span.generated-code[data-userid='" + userId + "']")
            .text(generatedPassword)
            .attr("data-initial", "false");

        $.ajax({
            url: "dbHandlers/handleLoadEmployee.php",
            method: "POST",
            data: {
                action: "generatePassword",
                userId: userId,
                password: generatedPassword,
            },
            success: function (response) {
                var res = JSON.parse(response);
                if (res.status === "success") {
                    alert("Password generated: " + generatedPassword);
                }
            },
            error: function () {
                alert("Error generating password.");
            },
        });
    });

    // Handle the destroy password button click
    $(".destroy-pass-button").click(function () {
        var userId = $(this).data("userid");
        $.ajax({
            url: "dbHandlers/handleLoadEmployee.php",
            method: "POST",
            data: { action: "destroyTempPassword", userId: userId },
            success: function (response) {
                var res = JSON.parse(response);
                if (res.status === "success") {
                    alert(res.message);
                    $("span.generated-code[data-userid='" + userId + "']")
                        .text("No temporary password")
                        .attr("data-initial", "true");
                }
            },
            error: function () {
                alert("Error destroying password.");
            },
        });
    });

    // Restore "Password Encrypted" on page load for initial states
    $(document).ready(function () {
        $("span.generated-code[data-initial='true']").each(function () {
            $(this).text("Password Encrypted");
        });
    });
</script>
</body>

</html>