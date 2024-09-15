<!DOCTYPE html>
<html>

<head>
  <?php
  include 'conn.php';
  session_start();
  if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit;
  }
  ?>

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #343a40;
      color: #fff;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .title {
      display: flex;
      align-items: center;
    }

    .title h1 {
      margin: 0;
      font-size: 40px;
    }

    .title a {
      text-decoration: none;
      color: #fff;
      margin-left: 30px;
      font-weight: bold;
      font-size: 20px;
    }

    .right a {
      text-decoration: none;
      color: #fff;
      font-weight: bold;
      padding: 10px 20px;
      background-color: #dc3545;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .right a:hover {
      background-color: #c82333;
    }

    .container {
      display: flex;
      margin-top: 20px;
    }

    .sidebar {
      width: 20%;
      background-color: #fff;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .sidebar a {
      display: block;
      text-decoration: none;
      color: #343a40;
      font-weight: bold;
      padding: 10px 0;
      border-bottom: 1px solid #e9ecef;
      transition: color 0.3s ease;
    }

    .sidebar a:hover {
      color: #007bff;
    }

    .content {
      width: 80%;
      padding: 20px;
    }

    ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    li {
      cursor: pointer;
      padding: 10px;
      background-color: #f1f1f1;
      margin-bottom: 5px;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    li:hover {
      background-color: #e9ecef;
    }
  </style>
</head>

<body>
  <header>
    <div class="title">
      <h1>FUTSOL</h1>
      <a href="#">Home</a>
    </div>
    <?php
    // Check if the admin is logged in
    if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] == true) {
      ?>
      <div class="right">
        <a href="logoutadmin.php">Logout</a>
      </div>
      <?php
    }
    ?>
  </header>

  <div class="container">
    <div class="sidebar">
      <ul>
        <li><a href="adminownerdetails.php" onclick="showTable('table-owner')">Owner Details</a></li>
        <li><a href="adminfutsaldetails.php" onclick="showTable('table-futsal')">Futsal Details</a></li>
        <li><a href="adminplayerdetails.php" onclick="showTable('table-player')">Player Details</a></li>
        <li><a href="adminbookingdetails.php" onclick="showTable('table-booking')">Booking Details</a></li>
      </ul>
    </div>
    <div class="content">
      <!-- Content goes here -->
    </div>
  </div>

  <?php include 'adminfooter.php'; ?>
</body>

</html>