<html>
<?php
include 'conn.php';
session_start();

$loggedin = isset($_SESSION['player_id']) && $_SESSION['loggedin'] === true;

// If user is not logged in, redirect to login page but show futsal details
if (!$loggedin) {
    $redirect_to_login = true;
} else {
    $redirect_to_login = false;
}

$ground_id = $_GET['ground_id'];
$sql = "SELECT * FROM ground WHERE ground_id=$ground_id";
$result = mysqli_query($con, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $ground_id = $row['ground_id'];
    $ground_name = $row['ground_name'];
    $ground_location = $row['ground_location'];
    $ground_description = $row['ground_description'];
    $ground_description_formatted = nl2br($ground_description);
    $ground_image = $row['ground_image'];
    $contact = $row['contact'];
    $latitude = $row['ground_latitude'];
    $longitude = $row['ground_longitude'];
}

include 'nav.php'; // Ensure the nav bar reflects the logged-in status
?>

<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <style>
        /* General Styles */
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
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }

        .futsal-box {
            width: 100%;
            padding: 0;
            border-radius: 0;
            box-shadow: none;
            background-color: transparent;
        }

        .futsal-box img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .futsal-box h1 {
            text-align: center;
            font-size: 40px;
        }

        .futsal-box h4 {
            text-align: center;
            margin: 10px 0;
            font-size: 18px;
        }

        .description-container {
            padding: 0;
            margin: 10px 0;
            background-color: bisque;
        }

        .map {
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn-container button,
        .btn-container a {
            padding: 10px 20px;
            font-size: 16px;
            background-color: blue;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .btn-container button:hover,
        .btn-container a:hover {
            background-color: #ff6b6b;
        }

        .btn-container a {
            cursor: pointer;
        }
    </style>
</head>

<body>

    <section class="futsal-section">
        <div class="futsal-box">
            <img src="<?php echo $ground_image; ?>" alt="Futsal Ground">
            <h1><?php echo $ground_name; ?></h1>
            <h4>Location: <?php echo $ground_location; ?></h4>
            <h4>Contact: <?php echo $contact; ?></h4>
            <div class="description-container">
                <p><?php echo $ground_description_formatted; ?></p>
            </div>
            <div id="map" style="width: 100%; height: 400px;"></div>
            <div class="btn-container">
                <?php if ($redirect_to_login): ?>
                    <a id="login-button">Book Now</a>
                <?php else: ?>
                    <button onclick="redirectLogin(<?php echo $ground_id; ?>)">Book Now</button>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
        function redirectLogin(ground_id) {
            window.location.href = "booking.php?ground_id=" + ground_id;
        }

        document.getElementById('login-button')?.addEventListener('click', function () {
            alert('Please login first.');
            window.location.href = "homepage.php";
        });

        var map = L.map('map').setView([<?php echo $latitude; ?>, <?php echo $longitude; ?>], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map)
            .bindPopup('<?php echo $ground_name; ?>')
            .openPopup();
    </script>
</body>
<?php include 'footer.php'; ?>

</html>