<!DOCTYPE html>
<html>
<?php
include 'conn.php'
    ?>
<?php
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
        }

        .left-section {
            flex: 1;
        }

        .left-section img {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            margin-bottom: 10px;
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
    </style>
    <title>Booking Page</title>
</head>

<body>
    <header>
        <div class="title">
            <a href="homepage.html">
                <h1>FUTSOL
                </h1>
            </a>
        </div>

        <?php
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

        <!-- Display name -->
        <?php
        if ($loggedin) {
            echo '
    <div class="welcome">';
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                $fullname = $_SESSION['fullname'];
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

    <!-- php -->

    <?php
    $ground_id = $_GET['ground_id'];
    $sql = "SELECT * FROM ground WHERE ground_id=$ground_id";
    $result = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $ground_name = $row['ground_name'];
        $ground_location = $row['ground_location'];
        $ground_description = $row['ground_description'];
        $ground_image = $row['ground_image'];
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
                <p>Location:
                    <?php echo $ground_location ?>
                </p>
                <p>Description:
                    <?php echo $ground_description ?>
                </p>
            </div>
            <div class="right-section">
                <h2>Booking Details</h2>
                <p>Select Date:</p>
                <?php
                // Get the current date
                $currentDate = date('Y-m-d');

                // Calculate the maximum date allowed (current date + 2 days)
                $maxDate = date('Y-m-d', strtotime($currentDate . ' + 2 days'));
                ?>
                <input type="date" id="booking-date" min="<?php echo $currentDate; ?>" max="<?php echo $maxDate; ?>">
                <div id="time-table">
                    <!-- Time slots will be dynamically generated here -->
                </div>
                <button id="confirm-booking">Proceed for Payment</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>


</body>

</html>