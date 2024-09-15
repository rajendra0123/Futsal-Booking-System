<!DOCTYPE html>
<html>
<?php
include 'conn.php';
//session_name("player_session");
session_start();
if (!isset($_SESSION['player_id']) || $_SESSION['loggedin'] !== true) {
  header('Location: homepage.php');
  exit;
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {

  $loggedin = true;
} else {
  $loggedin = false;

}
?>

<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      color: #333;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .futsal-section {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      padding: 30px;
      justify-content: center;
      flex: 1;
    }

    .futsal-box {
      width: 350px;
      background-color: #333;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .futsal-box:hover {
      transform: translateY(-10px);
    }

    .futsal-box img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 15px;
    }

    .futsal-box h3 {
      font-size: 22px;
      margin: 0 0 12px 0;
      color: white;
    }

    .futsal-box h4 {
      font-size: 18px;
      margin: 0 0 12px 0;
      color: white;
    }

    .futsal-box .btn-container {
      display: flex;
      justify-content: space-between;
    }

    .futsal-box button {
      padding: 12px 18px;
      font-size: 18px;
      background-color: blue;
      color: #ffffff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .futsal-box button:hover {
      background-color: #ff6b6b;
    }

    .hero-section {
      background-image: url('registerimage/nearby.jpeg');
      background-size: cover;
      background-position: center;
      height: 400px;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      text-align: center;
    }

    .hero-section h1 {
      font-size: 48px;
      margin: 0;
      z-index: 2;
    }

    .hero-section .mid {
      position: relative;
      z-index: 2;
      margin-top: 20px;
    }

    .hero-section::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1;
    }

    .btn-containers {
      display: flex;
      margin-left: -320px;

    }

    .btn-containers button {
      padding: 10px 20px;
      font-size: 16px;
      background-color: blue;
      color: #ffffff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
      margin: 10px;
    }

    .btn-containers button:hover {
      background-color: #ff6b6b;
    }

    /* .footer {
      margin-top: 50px;
      background-color: rgb(211, 241, 242);
      padding: 15px;
      border-radius: 5px;
      width: fit-content;
      margin-left: 5px;
    }

    .footer .regsiter-here button {
      padding: 8px 16px;
      font-size: 14px;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;

    }

    .footer .regsiter-here button:hover {
      opacity: 0.5;
    } */
  </style>
</head>

<header>

</header>

<body>
  <?php include 'nav.php'; ?>
  <div class="hero-section">
    <h1>Looking for Nearby Futsal?</h1>
    <div class="mid">
      <div class="btn-containers">
        <button onclick="window.location.href='nearfutsal.php'">Click Here</button>
      </div>
    </div>
  </div>

  <?php
  $sql = "SELECT * FROM ground";
  $result = mysqli_query($con, $sql);
  echo '<section class="futsal-section">';
  while ($row = mysqli_fetch_assoc($result)) {
    $ground_id = $row['ground_id'];
    $ground_name = $row['ground_name'];
    $ground_location = $row['ground_location'];
    $contact = $row['contact'];
    $ground_description = $row['ground_description'];
    // $ground_image = $row['ground_image'];
    $futsal_logo = $row['futsal_logo'];
    $status = $row['status'];
    if ($status === 'Verified') {
      echo '
  <div class="futsal-box">
    <img src="' . $futsal_logo . '" alt="Futsal Ground" height="100px">
    <h3>' . $ground_name . '</h3>
    <h4>' . $ground_location . '</h4>
    
    <div class="btn-container">
      <button onclick="redirectLogin(' . $ground_id . ')">Book Now</button>
      <button onclick="viewDetails(' . $ground_id . ')">View Details</button>
    </div>
  </div>';
    }
  }
  echo '</section>';
  ?>

  <!-- </div> -->

  <script>
    function redirectLogin(ground_id) {
      <?php if ($loggedin) { ?>
        // Redirect to the booking page with the respective ground_id
        window.location.href = "booking.php?ground_id=" + ground_id;
      <?php } else { ?>
        // Redirect to the login page
        window.location.href = "login.php";
      <?php } ?>
    }

    function viewDetails(ground_id) {
      console.log(ground_id);
      window.location.href = "futsaldetail.php?ground_id=" + ground_id;

    }
  </script>
  <?php include 'footer.php'; ?>
</body>

</html>