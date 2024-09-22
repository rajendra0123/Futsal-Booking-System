<!DOCTYPE html>
<html>

<?php
include 'conn.php';
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
} else {
    $loggedin = false;
}
?>


<head>
    <style>
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
<?php include 'nav.php'; ?>
<h1 align="center">Your Searched Futsals</h1>

<body>
    <?php


    $searchTerm = $_GET['search'];

    if (strlen($searchTerm) >= 5) {
        $sql = "SELECT * FROM ground WHERE (ground_name LIKE '%$searchTerm%' AND CHAR_LENGTH(ground_name) >= 5) OR (ground_location LIKE '%$searchTerm%' AND CHAR_LENGTH(ground_location) >= 5)";
        $result = mysqli_query($con, $sql);

        // Check if there are results
        if (mysqli_num_rows($result) > 0) {
            echo '<section class="futsal-section">';
            while ($row = mysqli_fetch_assoc($result)) {
                $ground_id = $row['ground_id'];
                $ground_name = $row['ground_name'];
                $ground_location = $row['ground_location'];
                $contact = $row['contact'];
                $ground_description = $row['ground_description'];
                $futsal_logo = $row['futsal_logo'];

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
            echo '</section>';
        } else {
            // No results found
            echo '<script>alert("No search results found")</script>';
            echo '<script>window.location.href = "homepage.php"</script>';
        }
    } else {
        echo '<script>alert("Search term must be at least 5 characters long.")</script>';
        echo '<script>window.location.href = "homepage.php"</script>';
    }
    ?>
    <?php
    if ($loggedin) {
        echo '
    <script>
        function redirectLogin(ground_id) {
            window.location.href = "booking.php?ground_id=" + ground_id;
        }

        function viewDetails(ground_id) {
            window.location.href = "futsaldetail.php?ground_id=" + ground_id;
        }
    </script>
';
    } else {
        echo '
    <script>
        function redirectLogin(ground_id) {
        alert("Please login first");
        }

        function viewDetails(ground_id) {
            window.location.href = "futsaldetail.php?ground_id=" + ground_id;
        }
    </script>
';
    }

    ?>

</body>

</html>