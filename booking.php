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
      background-color: transparent;
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

    .btn-container button:hover {
      background-color: #ff6b6b;
    }

    .booking-page {
      display: flex;
      gap: 30px;
      padding: 20px;
    }

    .booking-header {
      width: 100%;
      text-align: center;
    }

    .booking-body {
      display: flex;
      width: 100%;
      gap: 20px;
    }

    .left-section {
      width: 65%;
    }

    .left-section img {
      width: 100%;
      height: auto;
      object-fit: cover;
      margin-bottom: 10px;
    }

    .right-section {
      width: 35%;
      height: 250px;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
    }

    .right-section h2 {
      font-size: 24px;
      margin-bottom: 20px;
      text-align: center;
    }

    .right-section p {
      margin-bottom: 10px;
      font-size: 16px;
    }

    .right-section input[type="date"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .right-section button {
      width: 100%;
      padding: 12px;
      font-size: 18px;
      background-color: blue;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .right-section button:hover {
      background-color: #ff6b6b;
    }
  </style>
  <title>Booking Page</title>
</head>

<body>
  <header>
  </header>

  <?php
  include 'nav.php';
  $ground_id = $_GET['ground_id'];
  $sql = "SELECT * FROM ground WHERE ground_id=$ground_id";
  $result = mysqli_query($con, $sql);
  while ($row = mysqli_fetch_assoc($result)) {
    $ground_name = $row['ground_name'];
    $ground_location = $row['ground_location'];
    $ground_description = $row['ground_description'];
    // $ground_description_formatted = nl2br($ground_description);
    $ground_image = $row['ground_image'];
    $contact = $row['contact'];
    $latitude = $row['ground_latitude'];
    $longitude = $row['ground_longitude'];
  }
  ?>

  <div class="booking-page">
    <div class="left-section">
      <img src="<?php echo $ground_image; ?>" alt="Futsal Image">
      <h4 align="center">Location: <?php echo $ground_location; ?></h4>
      <h4 align="center">Contact: <?php echo $contact; ?></h4>
      <div class="description-container">
        <p class="description-text"><?php echo $ground_description; ?></p>
      </div>
    </div>

    <div class="right-section" align="center">
      <h2>Booking Details</h2>
      <p>Select Date:</p>
      <?php
      // Get the current date
      $currentDate = date('Y-m-d');

      // Calculate the maximum date allowed (current date + 2 days)
      $maxDate = date('Y-m-d', strtotime($currentDate . ' + 2 days'));
      ?>
      <input type="date" id="booking-date" min="<?php echo $currentDate; ?>" max="<?php echo $maxDate; ?>" required>
      <button id="see-time-slots" disabled>See Time Slots</button>
      <script>
        document.getElementById("booking-date").addEventListener("input", function () {
          var selectedDate = document.getElementById("booking-date").value;
          var seeTimeSlotsButton = document.getElementById("see-time-slots");

          if (selectedDate >= "<?php echo $currentDate; ?>" && selectedDate <= "<?php echo $maxDate; ?>") {
            seeTimeSlotsButton.disabled = false;
          } else {
            seeTimeSlotsButton.disabled = true;
          }
        });
      </script>
    </div>

  </div>
  <div id="map" style="width: 100%; height: 400px;"></div>
  <script>


    document.getElementById("see-time-slots").addEventListener("click", function () {
      var selectedDate = document.getElementById("booking-date").value;
      var groundId = <?php echo $ground_id; ?>;
      window.location.href = "timeslot.php?ground_id=" + groundId + "&selectedDate=" + selectedDate;
    });
  </script>

  </div>
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