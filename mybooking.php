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
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            display: flex;
            justify-content: center;
            margin: 20px;
        }

        .content {
            width: 100%;
            max-width: 1200px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        h3 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
        }

        tr:hover {
            background-color: #ddd;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .action-btns {
            display: flex;
            gap: 5px;
        }

        .action-btns form {
            margin: 0;
        }

        .action-btns input[type="submit"] {
            background-color: #f44336;
        }

        .action-btns input[type="submit"]:hover {
            background-color: #e53935;
        }

        .action-btns .verify-btn {
            background-color: #4CAF50;
        }

        .action-btns .verify-btn:hover {
            background-color: #45a049;
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
            <table id="table-player">
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

                    $player_id = $_SESSION['player_id'];

                    $sql = "SELECT p.email,g.ground_name, g.ground_location, g.contact, g.amount, b.booking_id, b.booking_date, b.booking_time,b.created_at, b.status
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
                            $createdAt = $row['created_at'];

                            date_default_timezone_set('Asia/Kathmandu');

                            $currentDateTime = time();
                            $bookingCreatedAt = strtotime($createdAt);

                            $timeDifference = ($currentDateTime - $bookingCreatedAt) / 60;


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
                            <td>';

                            if ($timeDifference <= 45) {
                                echo '<form method="POST" action="">
                                        <input type="submit" name="delete" value="Cancel">
                                      </form>';
                            } else {
                                echo 'Cancellation not allowed';
                            }
                            echo '</td>
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