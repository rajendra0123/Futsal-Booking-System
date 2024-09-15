<!DOCTYPE html>
<html>
<?php
session_start();
include 'conn.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
} else {
    $loggedin = false;
}

if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = "SELECT * FROM `owner` WHERE email='$email'";
    $result = mysqli_query($con, $query);
    if (!$result || mysqli_num_rows($result) == 0) {
        header("Location: homepage.php");
        exit();
    }
    $row = mysqli_fetch_assoc($result);
    $fullname = $row['fullname'];
    $owner_id = $row['owner_id'];
}
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
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .futsal-box {
            display: flex;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
        }

        .futsal-details-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-image-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
            gap: 20px;
        }

        .futsal-logo {
            width: 30%;
            height: auto;
            max-height: 700px;
            border-radius: 5px;
            margin-left: -300px;
        }

        .ground-image {
            width: 200%;
            height: 450px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: -300px;
        }

        .map-container {
            width: 100%;
            margin-top: 20px;
        }

        #map {
            width: 100%;
            height: 400px;
            /* Adjust the height */
            background-color: #f0f0f0;
        }

        h1,
        h4 {
            text-align: center;
        }

        .description-container {
            width: 100%;
            padding: 20px;
        }

        .description-text {
            text-align: center;
            white-space: pre-wrap;
            word-wrap: break-word;
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

        .btn-container a,
        .btn-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: blue;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-container a:hover,
        .btn-container button:hover {
            background-color: #ff6b6b;
        }

        .details-container {
            background-color: pink;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            width: 150%;
            margin-top: -17px;
        }

        .details-container h1 {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 35px;
            text-align: center;
            color: #333;

        }

        .details-container h4 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 20px;
            text-align: center;
            color: #333;
            text-align: left;
        }

        .description-container {
            margin: 10px 0;
        }

        .description-container p {
            text-align: center;
        }

        .description-text {
            text-align: center;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .map-container {
            width: 100%;
            margin-top: 20px;
        }

        #map {
            width: 100%;
            height: 400px;
            background-color: #f0f0f0;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn-container a,
        .btn-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: blue;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-container a:hover,
        .btn-container button:hover {
            background-color: #ff6b6b;
        }
    </style>
</head>
<?php include 'nav.php'; ?>

<body>
    <section class="futsal-section">
        <?php
        if (isset($_GET['ground_id'])) {
            $selected_ground_id = $_GET['ground_id'];
            $query = "SELECT * FROM `ground` WHERE ground_id = '$selected_ground_id' AND owner_id = '$owner_id'";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $ground_name = $row['ground_name'];
                    $ground_location = $row['ground_location'];
                    $ground_description = $row['ground_description'];
                    $ground_description_formatted = nl2br($ground_description);
                    $ground_image = $row['ground_image'];
                    $futsal_logo = $row['futsal_logo'];
                    $contact = $row['contact'];
                    $amount = $row['amount'];
                    $longitude = $row['ground_longitude'];
                    $latitude = $row['ground_latitude'];
                    $status = $row['status'];

                    if ($status === 'Verified') {
                        echo '
                      <div class="futsal-details-container">
    <div class="logo-image-container">
        <img src="' . $futsal_logo . '" alt="Futsal Logo" class="futsal-logo">
        <img src="' . $ground_image . '" alt="Ground Image" class="ground-image">
    </div>
                              <div class="details-container">
    <h1>' . htmlspecialchars($ground_name) . '</h1>
    <h4>Location: ' . htmlspecialchars($ground_location) . '</h4>
    <h4>Contact: ' . htmlspecialchars($contact) . '</h4>
    <h4>Amount per hour: ' . htmlspecialchars($amount) . '</h4>
    <div class="description-container">
        <p class="description-text">' . $ground_description_formatted . '</p>
    </div>
    <div class="map-container">
        <div id="map"></div>
    </div>
    <div class="btn-container">
        <a href="editground.php?ground_id=' . $selected_ground_id . '">Edit</a>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="ground_id" value="' . $selected_ground_id . '">
            <button type="submit" name="delete">Delete</button>
        </form>
    </div>
</div>';
                    }
                }
            } else {
                echo '<p>No ground found.</p>';
            }
        }

        if (isset($_POST['delete'])) {
            $ground_id_to_delete = $_POST['ground_id'];
            $deleteQuery = "DELETE FROM `ground` WHERE ground_id='$ground_id_to_delete'";
            $deleteResult = mysqli_query($con, $deleteQuery);

            if ($deleteResult) {
                echo '<script>alert("Ground deleted successfully")</script>';
                echo '<script>window.location.href = "futsalregister.php"</script>';
                exit;
            } else {
                echo '<script>alert("Failed to delete ground: ' . mysqli_error($con) . '")</script>';
            }
        }

        ?>
    </section>

    <script>
        var map = L.map('map').setView([<?php echo $latitude; ?>, <?php echo $longitude; ?>], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map)
            .bindPopup('<?php echo $ground_name; ?>')
            .openPopup();
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>