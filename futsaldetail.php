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
            margin-right: 30px;
            margin-bottom: 20px;
            margin-top: 20px;
            margin-left: 20px;
            font-size: larger;
            font-weight: bold;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color: white;
        }

        button.search-button {
            border: none;
            background-color: transparent;
            padding: 0;
            cursor: pointer;
        }

        button.search-button:focus {
            outline: none;
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

        .dropdown a {
            text-decoration: none;
            color: black;
        }

        /* .futsal-section {
            display: flex;
            flex-wrap: wrap;
            gap: 75px;

        } */

        .futsal-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;

        }

        .futsal-box {
            width: 350px;
            height: 600px;
            padding: 20px;
            border-radius: 5px;

        }

        .futsal-box img {
            width: 200%;
            height: 350px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
            margin-left: -150px;
        }

        .futsal-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .futsal-box p {
            font-size: 14px;
            margin-bottom: 20px;
        }


        .futsal-box .btn-container {
            text-align: center;
            justify-content: space-between;

        }

        .futsal-box .btn-container button {
            padding: 8px 16px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .futsal-section .futsal-box .btn-container button:hover {
            opacity: 0.5;
        }

        .description-container {
            width: 500px;
            word-wrap: break-word;
            margin-left: -48px;
        }

        .description-text {
            margin-top: 10px;
            text-align: left;
            white-space: pre-wrap;
            word-wrap: break-word;

        }

        .map {
            margin-left: -100px;
            margin-bottom: 50px;
        }
    </style>
</head>
<header>
    <div class="title">
        <h1>FUTSOL</h1>
    </div>
    <?php
    if ($loggedin) {
        echo '
    <div class="mid">
        <form method="GET" action="search.php">
            <input type="search" name="search" placeholder="Search By Name or Location" size="37" />
            <button type="submit" class="search-button">
                <img src="searchlogo.png" class="search-logo">
            </button>
        </form>
    </div>

    <div class="navigation">

        <div class="navigation">
            <a href="groundlist.php">GROUNDS</a>&nbsp;&nbsp;&nbsp;
        </div>
      

        <div class="dropdown">
            <img src="loginimage.png" alt="User Image" class="user-image" height="55px">

            <a href="logout.php">Logout</a>
        </div>
    </div>
';
        ?>
        <?php
        $player_id = $_SESSION['player_id'];
        $sql = "SELECT * FROM player WHERE player_id = $player_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $player_id = $row['player_id'];
        $fullname = $row['fullname'];
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
    }
    ?>
</header>

<?php
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
    $map = $row['map'];
}
?>

<body>
    <section class="futsal-section">
        <div class="futsal-box">
            <img src="<?php echo $ground_image; ?>" alt="Futsal Ground 1">
            <h1 align="center">
                <?php echo $ground_name ?>
            </h1>
            <h4 align="center">Location:
                <?php echo $ground_location ?>
            </h4>
            <h4 align="center">Contact:
                <?php echo $contact ?>
            </h4>
            <h4 class="description-title" align="center">Description:</h4>
            <div class="description-container">
                <p>
                    <?php echo $ground_description_formatted ?>
                </p>
            </div>
            <div class="map" align="center">
                <?php echo $map ?>
            </div>
            <div class="btn-container">
                <button onclick="redirectLogin(' . $ground_id . ')">Book Now</button>
            </div>
        </div>
    </section>
</body>

<script>
    function redirectLogin() {
        <?php
        if ($loggedin) {
            echo '
            window.location.href = "booking.php?ground_id=' . $ground_id . '";
            ';
        } else {
            echo '
            window.location.href = "login.php";';
        }
        ?>
    }
</script>

</html?