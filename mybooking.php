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
    <style>
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
    </header>
    <?php include 'nav.php'; ?>

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
    <?php include 'footer.php'; ?>
</body>

</html>