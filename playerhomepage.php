<!DOCTYPE html>
<html>
<?php
//session_name("player_session");
session_start();
include 'conn.php';

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
        .btn-containers {
            margin-left: -720px;
            margin-top: 20px;
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

        .hero-section {
            background-image: url('registerimage/nearby.jpeg');
            background-size: cover;
            background-position: center;
            height: 400px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 48px;
            margin: 0;
            z-index: 2;
            margin-right: 220px;
        }

        .hero-section .mid {
            position: absolute;
            bottom: 110px;
            z-index: 2;

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


        .futsol {
            margin-left: 150px;
        }
    </style>
</head>

<header>
</header>

<body>

    <?php include 'nav.php' ?>
    <div class="hero-section">
        <h1>Looking for Nearby Futsal?</h1>
        <div class="mid">
            <div class="btn-containers">
                <button onclick="window.location.href='nearfutsal.php'">Find Here</button>
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
        $futsal_logo = $row['futsal_logo'];
        $status = $row['status'];
        if ($status === 'Verified') {
            echo '
        <div class="futsal-box">
            <img src="' . $futsal_logo . '" alt="Futsal Ground" height="100px">
            <h3>' . $ground_name . '</h3>
            <div class="btn-container">
                <button onclick="redirectLogin(' . $ground_id . ')">Book Now</button>
                <button onclick="viewDetails(' . $ground_id . ')">View Details</button>
            </div>
            
        </div>';
        }
    }

    echo '</section>';
    ?>
    <script>
        function redirectLogin(ground_id) {
            window.location.href = "booking.php?ground_id=" + ground_id;
        }

        function viewDetails(ground_id) {
            window.location.href = "futsaldetail.php?ground_id=" + ground_id;
        }

    </script>
    <?php include 'footer.php' ?>
</body>

</html>