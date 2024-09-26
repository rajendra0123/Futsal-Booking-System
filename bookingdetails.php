<html>
<?php
//session_name("owner_session");
session_start();
if (isset($_SESSION['owner_id']) && $_SESSION['loggedin'] === true) {
} else {
    header("Location: homepage.php");
    exit();
}


if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
} else {
    $loggedin = false;
}
include 'conn.php';
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
<header>

</header>
<?php include 'nav.php'; ?>

<body>


    <div class="container">
        <div class="content">
            <h3>Booking Details</h3>
            <table id="table-player">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Contact</th>
                        <th>Booking Date</th>
                        <th>Booking Time</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $owner_id = $_SESSION['owner_id'];
                    $sql = "SELECT p.fullname, p.email, p.contact, b.booking_id, b.ground_id, b.booking_date, b.booking_time, b.payment, b.status
    FROM booking b
    LEFT JOIN player p ON p.player_id = b.player_id
    WHERE b.owner_id = $owner_id";

                    $result = mysqli_query($con, $sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $fullName = $row['fullname'] ? $row['fullname'] : 'N/A';
                            $contact = $row['contact'] ? $row['contact'] : 'N/A';
                            $amount = $row['payment'] ? $row['payment'] : 'N/A';
                            $booking_id = $row['booking_id'];
                            $booking_time = $row['booking_time'];
                            $booking_date = $row['booking_date'];
                            $status = $row['status'];

                            echo '
                            <tr>
                                <td>' . $fullName . '</td>
                                <td>' . $contact . '</td>
                                <td>' . $booking_date . '</td>
                                <td>' . $booking_time . '</td>
                                <td>' . $amount . '</td>
                                <td>';


                            if ($status === 'Verified') {
                                echo 'Booked';
                            }
                            // } else if ($status === 'Pending') {
                            //     echo '
                            //         <form method="POST" action="bookingdetails.php">
                            //             <input type="hidden" name="booking_id" value="' . $booking_id . '">
                            //             <input type="submit" value="Verify" name="verify" class="verify-btn">
                            //         </form>';
                            // }
                    
                            echo '
                                </td>
                                <td class="action-btns">
                                    <form method="POST" action="bookingdetails.php">
                                        <input type="hidden" name="booking_id" value="' . $booking_id . '">
                                        <input type="submit" value="Delete" name="delete" class="delete-btn">
                                    </form>
                                </td>
                            </tr>';
                        }
                    } else {
                        echo "Error: " . mysqli_error($con);
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>
    <?php
    if (isset($_POST['verify'])) {
        var_dump($_POST);
        $booking_id = $_POST['booking_id'];

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
    <?php include 'footer.php'; ?>
</body>

</html>