<!DOCTYPE html>
<html>
<?php
include 'conn.php';
session_name("player_session");
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  $loggedin = true;
} else {
  $loggedin = false;

}
?>

<head>
  <style>
    .title {
      margin-right: 50px;
      margin-left: 30px;
      width: "50%";
      color: rgb(7, 7, 7);
      font-size: larger;

    }

    .title a {
      text-decoration: none;
      color: #000;
    }

    header {
      background-color: #f6e2e2;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .welcome {
      display: flex;
      align-items: center;
      background-color: grey;
      height: 50px;
      border-radius: 10px;
      padding: 8px;
    }

    .welcome p {
      margin-right: 30px;
      margin-bottom: 20px;
      margin-top: 20px;
      margin-left: 20px;
      font-size: larger;
      font-weight: bold;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
      color: white;
    }

    .navigation {
      display: flex;
      align-items: center;
    }

    .navigation a {
      text-decoration: none;
      margin-right: 10px;
      color: black;
      font-size: larger;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;

    }

    .dropdown {
      margin-left: 750px;
    }

    .dropdown a {
      display: flex;
      text-decoration: none;
      color: black;

    }

    .logo {
      margin-right: 20px;
    }

    .header-links {
      text-align: right;
    }

    .header-links a {
      color: #fff;
      text-decoration: none;
      margin-left: 10px;
    }

    .booking-page {
      margin: 50px auto;
      width: 80%;
      text-align: center;
    }

    .booking-header {
      margin-bottom: 20px;
    }

    .booking-header h1 {
      font-size: 24px;
    }

    .booking-body {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      text-align: left;
    }

    .left-section {
      flex: 1;
    }

    .left-section img {
      width: 100%;
      max-height: 350px;
      object-fit: cover;
      margin-bottom: 10px;
      border-radius: 5px;

    }

    #see-time-slots {
      cursor: pointer;
    }

    .right-section {
      flex: 1;

    }

    .right-section h2 {
      font-size: 18px;
      margin-bottom: 10px;
    }

    #time-table {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 5px;
      margin-bottom: 10px;
    }

    #confirm-booking {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;

    }

    #confirm-booking button:hover {
      opacity: 0.5;
    }

    .description-container {
      width: 600px;
      word-wrap: break-word;
    }

    .description-text {
      margin-top: 10px;
      white-space: pre-wrap;
      word-wrap: break-word;

    }

    /* .map {
      margin-top: -100px;
    } */
  </style>
  <title>Booking Page</title>
</head>

<body>
  <header>
    <div class="title">

      <h1>FUTSOL
      </h1>
    </div>

    <?php
    $player_id = $_SESSION['player_id'];
    $sql = "SELECT * FROM player WHERE player_id = $player_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $player_id = $row['player_id'];
    $fullname = $row['fullname'];
    if ($loggedin) {
      echo '
  <div class="navigation">
    <a href="playerhomepage.php">HOME</a>
    
    </div>';
    }
    ?>

    <?php
    if ($loggedin) {
      echo '
        <div class="dropdown">
            <img src="loginimage.png" alt="User Image" class="user-image" height="55px">
        
        <a href="logout.php">Logout</a>
    </div>
</div>';
    }
    ?>
    <?php
    if ($loggedin) {
      echo '
    <div class="welcome">';
      if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        // $fullname = $_SESSION['fullname'];
        echo "<p>$fullname</p>";
      } else {
        header("Location: login.php");
        exit;
      }
      echo '
    </div>
    </div>';
    }
    ?>
  </header>

  <?php
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
    $map = $row['map'];
  }
  ?>

  <div class="booking-page">
    <div class="booking-header">
      <h1>
        <?php echo $ground_name ?>
      </h1>
    </div>
    <div class="booking-body">
      <div class="left-section">
        <img src="<?php echo $ground_image; ?>" alt="Futsal Image">
        <h4 align="center">Location:
          <?php echo $ground_location ?>
        </h4>
        <h4 align="center">Contact:
          <?php echo $contact ?>
        </h4>
        <h4 class="description-title" align="center">Description:</h4>
        <div class="description-container">
          <p class="description-text">
            <?php echo $ground_description ?>
          </p>
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
        <input type="date" id="booking-date" min="<?php echo $currentDate; ?>" max="<?php echo $maxDate; ?>">
        <button id="see-time-slots">See Time Slots</button>

      </div>
    </div>
  </div>
  <script>


    document.getElementById("see-time-slots").addEventListener("click", function () {
      var selectedDate = document.getElementById("booking-date").value;
      var groundId = <?php echo $ground_id; ?>;
      window.location.href = "timeslot.php?ground_id=" + groundId + "&selectedDate=" + selectedDate;
    });
  </script>
  <div class="map" align="center">
    <?php echo $map ?>
  </div>


</body>

</html>