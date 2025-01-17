<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Client Registration</title>
  <link rel="stylesheet" href="includes/homepage.css">
</head>

<body>
  <div class="container">
    <div class="box">
      <h2>Client Registration</h2>
      <form method="POST" action="register_client.php">
        <label>First Name: <input type="text" name="first_name" required></label><br><br>
        <label>Last Name: <input type="text" name="last_name" required></label><br><br>
        <label>Email Address: <input type="email" name="email" required></label><br><br>
        <button type="submit" class="btn">Register</button>
      </form>
    </div>
  </div>
</body>

</html>