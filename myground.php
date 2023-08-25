<html>
<?php
session_name("owner_session");
session_start();
include 'conn.php';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
} else {
    $loggedin = false;
}
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

        .welcome {
            display: flex;
            align-items: center;
            background-color: grey;
            height: 50px;
            border-radius: 10px;
            padding: 8px;
        }

        .welcome p {

            margin-right: 10px;
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
            margin-left: 380px;
        }

        .dropdown a {
            display: flex;
            text-decoration: none;
            color: black;

        }

        .booking {
            width: 20%;
            margin-top: 35px;
        }

        #see-time-slots {
            cursor: pointer;
        }

        .booking a {
            text-decoration: none;
            color: black;
            font-weight: bold;
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

        .futsal-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            margin: 0;

        }

        .futsal-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 800px;
            height: 2000px;
            padding: 20px;
            border-radius: 5px;
            margin-top: 400px;
        }

        .image-container {
            margin-top: 300px;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;

        }

        .logo-container,
        .image-content {
            width: 50%;
            text-align: center;
            margin-right: 50px;
        }

        .futsal-logo {
            height: 50px;
            width: 50px;
        }

        .futsal-logo {
            height: 50px;
            width: 50px;
        }

        .ground-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 5px;
        }

        .futsal-box img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;

        }

        .futsal-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .futsal-box p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .futsal-box input[type="submit"],
        .futsal-box a {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .description-container {
            width: 600px;
            word-wrap: break-word;
        }

        .description-text {
            margin-top: 10px;
            text-align: left;
            white-space: pre-wrap;
            word-wrap: break-word;

        }

        .map {
            margin-bottom: 50px;
            margin-top: 10px;
        }
    </style>
</head>
<header>
    <div class="title">
        <h1>FUTSOL</h1>
        </a>
    </div>
    <div class="navigation">
        <div class="dropdown">
            <img src="loginimage.png" alt="owner Image" class="owner-image" height="55px">

            <a href="logout.php">Logout</a>
        </div>
    </div>
    <?php
    if ($loggedin) {
        $owner_id = $_SESSION['owner_id'];
        $sql = "SELECT * FROM owner WHERE owner_id = $owner_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $owner_id = $row['owner_id'];
        $fullname = $row['fullname'];
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

<body>
    <div class="booking">
        <ul>
            <li> <a href="bookingdetails.php">
                    <h3>Booking Details</h3>
                </a>
            </li>
            <li>
                <h3>Booking TimeSlots</h3>
                <div class="right-section">
                    <?php
                    $ground_id = $_GET['ground_id'];
                    // Get the current date
                    $currentDate = date('Y-m-d');
                    // Calculate the maximum date allowed (current date + 2 days)
                    $maxDate = date('Y-m-d', strtotime($currentDate . ' + 2 days'));
                    ?>
                    <input type="date" id="booking-date" min="<?php echo $currentDate; ?>"
                        max="<?php echo $maxDate; ?>">
                    <button id="see-time-slots">See Time Slots</button>

                </div>
                <script>
                    document.getElementById("see-time-slots").addEventListener("click", function () {
                        var selectedDate = document.getElementById("booking-date").value;
                        var groundId = <?php echo $ground_id; ?>;
                        window.location.href = "bookingtimeslots.php?ground_id=" + groundId + "&selectedDate=" + selectedDate;
                    });
                </script>
            </li>
        </ul>
    </div>

    <section class="futsal-section">

        <?php
        $email = $_SESSION['email'];
        $query = "SELECT * FROM `owner` WHERE email = '$email'";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $owner_id = $row['owner_id'];
            $query = "SELECT * FROM `ground` WHERE owner_id = '$owner_id'";
            $result = mysqli_query($con, $query);

            while ($row = mysqli_fetch_assoc($result)) {

                $ground_name = $row['ground_name'];
                $ground_location = $row['ground_location'];
                $ground_description = $row['ground_description'];
                // $ground_description_formatted = nl2br($ground_description);
                $ground_image = $row['ground_image'];
                $ground_id = $row['ground_id'];
                $futsal_logo = $row['futsal_logo'];
                $contact = $row['contact'];
                $amount = $row['amount'];
                $status = $row['status'];
                $map = $row['map'];
                if ($status === 'Verified') {
                    echo '
              <form method="POST">  
              <div class="futsal-box">
              <div class="image-container">
                  <div class="logo-container">
                  <h2>Logo</h2>
                      <img src="' . $futsal_logo . '" class="futsal-logo" alt="<?php echo $ground_name; ?>">
                  </div>
                  <div class="image-content">
                 <h2> Ground Image</h2>
                      <img src="' . $ground_image . '" class="ground-image" alt="<?php echo $ground_name; ?>">
                  </div>
              </div>
                <h1>' . $ground_name . '</h1>
                <h4>Location: ' . $ground_location . '</h4>
                <h4>Contact:' . $contact . '</h4>
                <h4>Amount per hour:' . $amount . '</h4>
                <h4 class="description-title">Description:</h4>
                <div class="description-container">
                <p class="description-text">' . $ground_description . '</p>
                </div>
                <div class="map" >
                ' . $map . '
                     </div>
                <a href="editground.php?ground_id=' . $ground_id . '">Edit</a><br>
                <input type="submit" value="delete" name="delete"/>  
                

</form>
</div>
            </div>';

                }
                if (isset($_POST['delete'])) {
                    // Perform the deletion query
                    $deleteQuery = "DELETE FROM `ground` WHERE ground_id='$ground_id'";
                    $deleteResult = mysqli_query($con, $deleteQuery);

                    if ($deleteResult) {
                        echo '<script>alert("Ground deleted successfully")</script>';
                        echo '<script>window.location.href = "futsalregister.php"</script>';
                        exit;
                    } else {
                        echo '<script>alert("Failed to delete ground: ' . mysqli_error($con) . '")</script>';
                    }
                }
            }
        }
        ?>

    </section>
</body>

</html>