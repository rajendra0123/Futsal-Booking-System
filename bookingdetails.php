<html>
<?php
session_name("owner_session");
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
} else {
    $loggedin = false;
}
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

        .container {
            display: flex;
            margin-left: 200px;
        }

        .content {
            width: 70%;
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

        table {
            width: 100%;
        }

        .content {
            margin-top: 80px;
            /* margin-left: 1px; */
        }

        table {
            width: 100%;
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
<header>
    <div class="title">
        <h1>FUTSOL</h1>
        </a>
    </div>
    <div class="dropdown">
        <img src="loginimage.png" alt="owner Image" class="owner-image" height="55px">

        <a href="logout.php">Logout</a>
    </div>
    </div>



    <?php
    $owner_id = $_SESSION['owner_id'];
    $sql = "SELECT * FROM owner WHERE owner_id = $owner_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $owner_id = $row['owner_id'];
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
    ?>

</header>

<body>


    <div class="container">
        <div class="content">

            <h3 align="center">Booking Details</h3>
            <table id="table-player" class="hidden">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Contact</th>
                        <th>Payment Screenshot</th>
                        <th>Booking Date</th>
                        <th>Booking Time</th>
                        <th>Verify</th>
                        <th>Delete</th>
                        <!-- <th>Revenue</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($loggedin) {
                        $owner_id = $_SESSION['owner_id'];
                        $sql = "SELECT p.fullname, p.email, p.contact, b.booking_id, b.ground_id, b.booking_date, b.booking_time, b.payment, b.status
                FROM player p
                JOIN booking b ON p.player_id = b.player_id
                WHERE b.owner_id = $owner_id";

                        $result = mysqli_query($con, $sql);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $fullName = $row['fullname'];
                                $contact = $row['contact'];
                                $payment = $row['payment'];
                                $booking_time = $row['booking_time'];
                                $booking_date = $row['booking_date'];
                                $status = $row['status'];

                                echo '
                <tr>
                    <td>' . $fullName . '</td>
                    <td>' . $contact . '</td>
                    <td><img src="' . $payment . '" height="100px" width="120px"></td>
                    <td>' . $booking_date . '</td>
                    <td>' . $booking_time . '</td>
                    <td>';

                                if ($status === 'Verified') {
                                    echo 'Verified';
                                } else {
                                    echo '
                        <form method="POST">
                            <input type="hidden" name="booking_id" value="' . $row['booking_id'] . '">';

                                    if ($status === 'pending') {
                                        echo '
                            <input type="submit" value="Verify" name="verify">';
                                    } else {
                                        echo '
                            <input type="submit" value="Verify" name="verify" disabled>';
                                    }

                                    echo '
                        </form>';
                                }

                                echo '
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="booking_id" value="' . $row['booking_id'] . '">
                            <input type="submit" value="Delete" name="delete">
                        </form>
                    </td>
                </tr>';
                            }
                        } else {
                            // Display an error message if the query execution fails
                            echo "Error: " . mysqli_error($con);
                        }
                    } else {
                        header("Location: login.php");
                        exit;
                    }
                    ?>
                    <?php
                    if (isset($_POST['verify'])) {
                        $booking_id = $_POST['booking_id'];

                        // Perform the verification logic here
                    
                        // Update the booking status to "Verified" in the database
                        $updateQuery = "UPDATE booking SET status = 'Verified' WHERE booking_id = $booking_id";
                        $updateResult = mysqli_query($con, $updateQuery);

                        if ($updateResult) {
                            // Show the confirmation message
                            echo '<script>alert("Booking verified successfully");</script>';
                            echo '<script>window.location.href = "bookingdetails.php"</script>';
                        } else {
                            // Show the error message if the update fails
                            echo '<script>alert("Failed to verify booking: ' . mysqli_error($con) . '");</script>';
                        }
                    }

                    if (isset($_POST['delete'])) {
                        $booking_id = $_POST['booking_id'];

                        // Delete the booking from the database
                        $deleteQuery = "DELETE FROM booking WHERE booking_id = $booking_id";
                        $deleteResult = mysqli_query($con, $deleteQuery);

                        if ($deleteResult) {
                            // Show the confirmation message
                            echo '<script>alert("Booking deleted successfully");</script>';
                            echo '<script>window.location.href = "bookingdetails.php"</script>';
                        } else {
                            // Show the error message if the deletion fails
                            echo '<script>alert("Failed to delete booking: ' . mysqli_error($con) . '");</script>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>