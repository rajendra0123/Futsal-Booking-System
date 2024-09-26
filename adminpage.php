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


  // Fetch total users
  $sqlTotalUsers = "
  SELECT 
      (SELECT COUNT(*) FROM player) AS total_players,
      (SELECT COUNT(*) FROM owner) AS total_owners
";
  $resultTotalUsers = mysqli_query($con, $sqlTotalUsers);
  $rowTotalUsers = mysqli_fetch_assoc($resultTotalUsers);
  $totalPlayers = $rowTotalUsers['total_players'];
  $totalOwners = $rowTotalUsers['total_owners'];
  $totalUsers = $totalPlayers + $totalOwners;



  // Fetch total revenue for all grounds
  $sqlRevenue = "
SELECT 
    SUM(g.amount) AS total_revenue,
    COUNT(b.booking_id) AS total_bookings
FROM ground g
JOIN booking b ON g.ground_id = b.ground_id
WHERE b.status = 'Verified';";
  $resultRevenue = mysqli_query($con, $sqlRevenue);
  $rowRevenue = mysqli_fetch_assoc($resultRevenue);
  $totalRevenue = $rowRevenue['total_revenue'];

  // Fetch today's bookings
  $sqlTodaysBookings = "SELECT COUNT(*) AS todays_bookings FROM booking WHERE DATE(created_at) = CURDATE()";
  $resultTodaysBookings = mysqli_query($con, $sqlTodaysBookings);
  $rowTodaysBookings = mysqli_fetch_assoc($resultTodaysBookings);
  $todaysBookings = $rowTodaysBookings['todays_bookings'];

  // Fetch this month's bookings
  $sqlMonthlyBookings = "SELECT COUNT(*) AS monthly_bookings FROM booking WHERE MONTH(booking_date) = MONTH(CURDATE())";
  $resultMonthlyBookings = mysqli_query($con, $sqlMonthlyBookings);
  $rowMonthlyBookings = mysqli_fetch_assoc($resultMonthlyBookings);
  $monthlyBookings = $rowMonthlyBookings['monthly_bookings'];

  // Fetch recent transactions
  $sqlRecentTransactions = "
  SELECT 
      COALESCE(player.fullname, 'Unknown') AS fullname, 
      g.amount AS payment, 
      booking.booking_date, 
      booking.booking_time 
  FROM booking 
  LEFT JOIN player ON booking.player_id = player.player_id 
  JOIN ground g ON booking.ground_id = g.ground_id
  WHERE booking.status = 'Verified' 
  ORDER BY booking.created_at DESC 
  LIMIT 5";

  $resultRecentTransactions = mysqli_query($con, $sqlRecentTransactions);
  ?>


  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

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

    .sidebar h2 {
      font-size: 18px;
      color: #4a148c;
      margin-bottom: 20px;
    }

    .sidebar ul {
      list-style-type: none;
      padding: 0;
    }

    .sidebar ul li {
      padding: 10px 0;
    }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #343a40;
      font-weight: bold;
      padding: 10px;
      border-radius: 8px;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .sidebar ul li a:hover {
      background-color: #f3e5f5;
      color: #4a148c;
    }

    .sidebar ul li a i {
      margin-right: 10px;
      font-size: 18px;
      color: #4a148c;
    }

    .admin-stats {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
    }

    .stat-item {
      background-color: #fff;
      padding: 20px;
      width: 200px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-align: center;
      border-radius: 8px;
    }

    .stat-item h3 {
      margin-bottom: 10px;
      font-size: 16px;
      color: #333;
    }

    .stat-item p {
      font-size: 24px;
      font-weight: bold;
      margin: 0;
      color: #4CAF50;
    }

    .recent-transactions {
      margin-top: 20px;
    }

    .recent-transactions table {
      width: 100%;
      border-collapse: collapse;
    }

    .recent-transactions th,
    .recent-transactions td {
      padding: 10px;
      border: 1px solid #ddd;
    }

    .recent-transactions th {
      background-color: #4CAF50;
      color: white;
    }

    .recent-transactions tr:hover {
      background-color: #f1f1f1;
    }
  </style>


  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

</head>

<body>
  <header>
    <div class="title">
      <h1>FUTSOL</h1>
      <a href="adminpage.php">HOME</a>
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
      <h2>Menu</h2>
      <ul>
        <li><a href="adminpage.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="adminownerdetails.php"><i class="fas fa-users"></i> Owner Details</a></li>
        <li><a href="adminfutsaldetails.php"><i class="fas fa-futbol"></i> Futsal Details</a></li>
        <li><a href="adminplayerdetails.php"><i class="fas fa-user-friends"></i> Player Details</a></li>
        <li><a href="adminbookingdetails.php"><i class="fas fa-calendar-alt"></i> Booking Details</a></li>
      </ul>
    </div>
    <div class="admin-stats" style="padding: 20px; width: 80%;">
      <div class="stat-item">
        <h3>Total Users</h3>
        <p><?php echo $totalUsers; ?></p>
      </div>

      <div class="stat-item">
        <h3>Total Revenue</h3>
        <p>NPR <?php echo number_format($totalRevenue, 2); ?></p>
      </div>

      <div class="stat-item">
        <h3>Today's Bookings</h3>
        <p><?php echo $todaysBookings; ?></p>
      </div>

      <div class="stat-item">
        <h3>This Month's Bookings</h3>
        <p><?php echo $monthlyBookings; ?></p>
      </div>

      <div class="recent-transactions" style="margin-top: 20px;">
        <h3>Recent Transactions</h3>
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr>
              <th style="padding: 10px; border: 1px solid #ddd;">Player Name</th>
              <th style="padding: 10px; border: 1px solid #ddd;">Payment</th>
              <th style="padding: 10px; border: 1px solid #ddd;">Booking Date</th>
              <th style="padding: 10px; border: 1px solid #ddd;">Booking Time</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($resultRecentTransactions) {
              while ($row = mysqli_fetch_assoc($resultRecentTransactions)) {
                echo '
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;">' . $row['fullname'] . '</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">NPR ' . number_format($row['payment'], 2) . '</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">' . $row['booking_date'] . '</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">' . $row['booking_time'] . '</td>
                        </tr>';
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php include 'adminfooter.php'; ?>
</body>

</html>