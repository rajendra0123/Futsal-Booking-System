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

        table {
            width: 100%;
        }

        /* .hidden {
                display: none;
            } */

        .content {
            margin-top: 80px;
            margin-left: 80px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            display: inline-block;
            margin: 0;
            padding: 0;
        }

        input[type="submit"] {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: black;
        }
    </style>
</head>

<body>
    <header>
        <div class="title">

            <h1>FUTSOL</h1>

        </div>

        <div class="mid">
            <form method="GET" action="search.php">
                <input type="search" name="search" placeholder="Search By Name or Location" size="37" />
                <button type="submit" class="search-button">
                    <img src="searchlogo.png" class="search-logo">
                </button>
            </form>
        </div>
        <?php
        if (!$loggedin) {
            echo '

    <a href="groundlist.php">GROUNDS</a>&nbsp;&nbsp;&nbsp;
    <a href="login.php">LOGIN</a>
    </div>';
        } else {
            echo '
    <div class="navigation">
        <a href="groundlist.php">GROUNDS</a>&nbsp;&nbsp;&nbsp;
        
    </div>';
        }
        ?>
        <?php
        $player_id = $_SESSION['player_id'];
        $sql = "SELECT * FROM player WHERE player_id = $player_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $player_id = $row['player_id'];
        $fullname = $row['fullname'];

        if (!$loggedin) {
            echo '
  <div class="navigation">
    <a href="homepage.php">HOME</a>&nbsp;&nbsp;&nbsp;';
        }
        ?>
        <?php
        if (!$loggedin) {
            echo '
        <a href="groundlist.php">GROUNDS</a>&nbsp;&nbsp;&nbsp;
        <a href="login.php">LOGIN</a>
    </div>';
        }
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


    <div class="container">
        <div class="content">

            <h3 align="center">Booking Details</h3>
            <table id="table-player" class="hidden">
                <thead>
                    <tr>
                        <th>Futsal Name</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Booking Date</th>
                        <th>Booking Time</th>
                        <th>Status</th>
                        <th>Cancel</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($loggedin) {
                        $player_id = $_SESSION['player_id'];
                        $sql = "SELECT p.email,g.ground_name, g.ground_location, g.contact, g.amount, b.booking_id, b.booking_date, b.booking_time, b.status
                            FROM booking b
                            JOIN ground g ON g.ground_id = b.ground_id
                            JOIN player p ON p.player_id = b.player_id
                            WHERE b.player_id = $player_id";
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $email = $row['email'];
                                $ground_name = $row['ground_name'];
                                $contact = $row['contact'];
                                $ground_location = $row['ground_location'];
                                $booking_id = $row['booking_id'];
                                $booking_time = $row['booking_time'];
                                $booking_date = $row['booking_date'];
                                $amount = $row['amount'];
                                $status = $row['status'];

                                echo '
            <tr>
                <td>' . $ground_name . '</td>
                <td>' . $contact . '</td>
                <td>' . $ground_location . '</td>
                <td>' . $booking_date . '</td>
                <td>' . $booking_time . '</td>
                <td>';

                                if ($status === 'Verified') {
                                    echo 'Booked';
                                } else {
                                    echo 'Pending';
                                }

                                echo '</td>
                                <td>
                                <form method="POST" action="">
                                <input type="submit" name="delete" value="Cancel">
                                </form>
                                </td>
                    <td>';

                                if ($status === 'Verified') {
                                    echo '
                        <form method="POST" action="receipt.php">
                            <input type="hidden" name="receipt" value="receipt">
                            <input type="hidden" name="ground_name" value="' . $ground_name . '">
                            <input type="hidden" name="email" value="' . $email . '">
                            <input type="hidden" name="booking_date" value="' . $booking_date . '">
                            <input type="hidden" name="booking_time" value="' . $booking_time . '">
                            <input type="hidden" name="ground_location" value="' . $ground_location . '">
                            <input type="hidden" name="amount" value="' . $amount . '">
                            <input type="hidden" name="contact" value="' . $contact . '">
                            <input type="submit" value="Receipt" name="Receipt">
                        </form>';
                                } else {
                                    echo 'N/A';
                                }

                                echo '</td>
                </tr>';
                            }
                        }
                    }

                    //deletion
                    
                    if (isset($_POST['delete'])) {
                        // Perform the deletion query
                        $deleteQuery = "DELETE FROM `booking` WHERE booking_id='$booking_id'";
                        $deleteResult = mysqli_query($con, $deleteQuery);

                        if ($deleteResult) {
                            echo '<script>alert("Booking Deleted successfully")</script>';
                            echo '<script>window.location.href = "mybooking.php"</script>';
                            exit;
                        } else {
                            echo '<script>alert("Failed to delete: ' . mysqli_error($con) . '")</script>';
                        }
                    }
                    ?>


                </tbody>
            </table>
        </div>
    </div>
</body>

</html>