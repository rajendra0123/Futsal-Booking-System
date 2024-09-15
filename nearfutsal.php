<!DOCTYPE html>
<html>
<?php
include 'conn.php';
session_name("player_session");
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
        .dropdown a {
            text-decoration: none;
            color: black;
        }

        .futsal-section {
            display: flex;
            flex-wrap: wrap;
            gap: 75px;
            margin-top: 30px;

        }

        .mid {
            margin-left: 300px;
            width: 100%;
            align-items: center;
            display: flex;
            position: relative;
            height: 50px;
        }

        .mid img {
            height: 20px;
            margin-left: 8.5px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .futsal-section {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            padding: 30px;
            justify-content: center;
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
    </style>
</head>

<header>

</header>

<body>
    <?php include 'nav.php'; ?>
    <?php

    $sql = "SELECT * FROM player where player_id='$player_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $longitude = $row['longitude'];
    $latitude = $row['latitude'];
    $radius = 6;

    $sql = "
    SELECT *, 
           (6371 * acos(
               cos(radians($latitude)) 
               * cos(radians(ground_latitude)) 
               * cos(radians(ground_longitude) - radians($longitude)) 
               + sin(radians($latitude)) 
               * sin(radians(ground_latitude))
           )) AS distance
    FROM ground
    HAVING distance < $radius   
    ORDER BY distance
";

    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) == 0) {
        echo '<script>
        alert("No nearby futsal venues found.");
         window.location.href = "playerhomepage.php";
         </script>';
    } else {

        echo '<section class="futsal-section">';
        while ($row = mysqli_fetch_assoc($result)) {
            $ground_id = $row['ground_id'];
            $ground_name = $row['ground_name'];
            $ground_location = $row['ground_location'];
            $contact = $row['contact'];
            $ground_description = $row['ground_description'];
            $ground_longitude = $row['ground_longitude'];
            $ground_latitude = $row['ground_latitude'];
            $distance = $row['distance'];
            $futsal_logo = $row['futsal_logo'];
            $status = $row['status'];
            if ($status === 'Verified') {
                echo '
  <div class="futsal-box">
    <img src="' . $futsal_logo . '" alt="Futsal Ground" height="100px">
    <h3>' . $ground_name . '</h3>
    <h4>' . $ground_location . '</h4>
    <h4>Distance: ' . round($distance, 2) . ' km</h4>
    <div class="btn-container">
      <button onclick="redirectLogin(' . $ground_id . ')">Book Now</button>
      <button onclick="viewDetails(' . $ground_id . ')">View Details</button>
    </div>
  </div>';
            }
        }
        echo '</section>';
    }
    ?>


    <script>
        function redirectLogin(ground_id) {
            <?php if ($loggedin) { ?>

                window.location.href = "booking.php?ground_id=" + ground_id;
            <?php } else { ?>

                window.location.href = "login.php";
            <?php } ?>
        }

        function viewDetails(ground_id) {
            window.location.href = "futsaldetail.php?ground_id=" + ground_id;

        }
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>