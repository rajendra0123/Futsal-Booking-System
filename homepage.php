<!DOCTYPE html>
<html>
<?php
include 'conn.php';
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

        .futsal-section {
            display: flex;
            flex-wrap: wrap;
            gap: 75px;
            margin-top: 30px;
            /* justify-content: center; */
        }

        .futsal-box {
            width: 350px;
            height: 500px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .futsal-box .btn-container {
            display: flex;
            justify-content: space-between;

        }

        .futsal-box .btn-container button {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;

        }

        .futsal-section .futsal-box .btn-container button:hover {
            opacity: 0.5;
        }

        .futsol {
            margin-left: 150px;
        }

        .footer {
            margin-top: 50px;
            background-color: rgb(211, 241, 242);
            padding: 15px;
            border-radius: 5px;
            width: fit-content;
            margin-left: 5px;

        }

        .register-here {
            margin-top: 20px;
        }

        .register-here .btn-container {
            display: flex;
            justify-content: space-between;
        }

        .register-here .btn-container button {

            padding: 8px 16px;
            font-size: 14px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<header>
    <div class="title">
        <a href="homepage.html">
            <h1>FUTSOL</h1>
        </a>
    </div>

    <div class="mid">
        <form method="GET" action="search.php">
            <input type="search" name="search" placeholder="Search By Name or Location" size="37" />
            <button type="submit" class="search-button">
                <img src="searchlogo.png" class="search-logo">
            </button>
        </form>
    </div>

    <div class="navigation">
        <a href="homepage.php">HOME</a>&nbsp;&nbsp;&nbsp;

        <a href="groundlist.php">GROUNDS</a>&nbsp;&nbsp;&nbsp;
        <a href="login.php">LOGIN</a>
    </div>
    </div>
</header>

<body>
    <?php
    $sql = "SELECT * FROM ground LIMIT 4";
    $result = mysqli_query($con, $sql);
    echo '<section class="futsal-section">';
    while ($row = mysqli_fetch_assoc($result)) {
        $ground_name = $row['ground_name'];
        $ground_id = $row['ground_id'];
        $futsal_logo = $row['futsal_logo'];
        $status = $row['status'];
        if ($status === 'Verified') {

            echo '
      
        <div class="futsal-box">
            <img src="' . $futsal_logo . '" alt="Futsal Ground" height="100px">
            <h3>' . $ground_name . '</h3>
            <div class="btn-container">
                <button onclick="redirectLogin()">Book Now</button>
                <button onclick="viewDetails(' . $ground_id . ')">View Details</button>
            </div>
            
        </div>';


        }
    }
    echo '</section>';

    ?>
    <div class="register-here">
        <h2>Are you a Futsal owner?</h2>
        <div class="btn-container">
            <button onclick="register()">Add your Futsal</button>
        </div>
    </div>

    <!-- Booking redirect -->
    <script>
        function redirectLogin() {
            // Redirect to the login page
            window.location.href = ("login.php");

        }
    </script>

    <!-- View Details redirect -->

    <script>
        function viewDetails(ground_id) {
            window.location.href = "futsaldetail.php?ground_id=" + ground_id;
        }

    </script>

    <!-- Register redirect -->

    <script>
        function register() {
            // Redirect to the login page
            window.location.href = ("login.php");
        }</script>

</body>

</html>