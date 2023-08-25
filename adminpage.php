<!DOCTYPE html>
<html>

<head>
  <html>
  <?php
  include 'conn.php';
  session_start();
  ?>

  <head>
    <style>
      header {
        background-color: #f6e2e2;
        padding: 20px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .title {
        margin-right: 50px;
        margin-left: 30px;
        width: 50%;
        color: rgb(7, 7, 7);
        font-size: larger;
      }

      .title a {
        text-decoration: none;
        color: #000;
      }

      .left {
        margin-left: 600px;
      }

      .right {
        margin-top: 100px;
      }

      .right a {
        text-decoration: none;
        color: black;
        font-weight: bold;
        font-size: larger;

      }

      .container {
        display: flex;
      }

      .sidebar {
        width: 20%;
        margin-top: 35px;
      }

      .sidebar a {
        text-decoration: none;
        color: black;
        font-weight: bold;
      }

      .content {
        width: 70%;
      }

      ul {
        list-style-type: none;
        padding: 0;
      }

      li {
        cursor: pointer;
        padding: 10px;
        background-color: #f1f1f1;
        margin-bottom: 5px;
      }
    </style>
  </head>
  <header>
    <div class="title">

      <h1>FUTSOL</h1>
      <div class="left">
        <h1 align="center">Welcome!</h1>
      </div>
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

  </html>
  <style>

  </style>

</head>

<body>
  <div class="container">

    <div class="sidebar">
      <ul>
        <li><a href="adminownerdetails.php" onclick="showTable('table-owner')">Owner Details</a></li>
        <li><a href="adminfutsaldetails.php" onclick="showTable('table-futsal')">Futsal Details</a></li>
        <li><a href="adminplayerdetails.php" onclick="showTable('table-player')">Player Details</a></li>
        <li><a href="adminbookingdetails.php" onclick="showTable('table-booking')">Booking Details</a></li>
      </ul>
    </div>
  </div>
</body>

</html>