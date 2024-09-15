<?php
include 'conn.php';
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
} else {
    $loggedin = false;
}

$ground_id = $_GET['ground_id'];
$selectedDate = $_GET['selectedDate'];

// Generate the time slots (from 8 AM to 8 PM, with an interval of 1 hour)
$timeSlots = array();
$startHour = 8;
$endHour = 20;
for ($hour = $startHour; $hour <= $endHour; $hour++) {
    $timeSlots[] = date('H:i:s', strtotime($hour . ':00'));
}

// Fetch the booked time slots from the database for the selected date and ground
$query = "SELECT booking_time FROM booking WHERE ground_id = $ground_id AND booking_date = '$selectedDate'";
$result = mysqli_query($con, $query);
$bookedTimeSlots = array();
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookedTimeSlots[] = $row['booking_time'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Time Slots</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        .available {
            color: #4CAF50;
        }

        .booked {
            color: #f44336;
        }

        .not-available {
            color: red;
        }

        .btn-pay {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            border: none;
        }

        .btn-pay:hover {
            background-color: #45a049;
        }

        .availability-status {
            display: inline-block;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1>Time Slots for <?php echo htmlspecialchars($selectedDate); ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Time Slot</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                date_default_timezone_set('Asia/Kathmandu');

                // Get the current date in YYYY-MM-DD format
                $currentDate = date('Y-m-d');
                $currentTime = date('H:i:s');

                // Loop through each time slot
                foreach ($timeSlots as $timeSlot) {
                    $status = 'Available';
                    $class = 'available';

                    // Check if the selected date is the current date
                    $isCurrentDate = ($selectedDate === $currentDate);

                    // Check if the time slot is in the past
                    $isPastTimeSlot = ($isCurrentDate && $timeSlot < $currentTime);

                    // Check if the time slot is booked
                    if (in_array($timeSlot, $bookedTimeSlots)) {
                        $status = 'Booked';
                        $class = 'booked';
                    } elseif ($isPastTimeSlot) {
                        $status = 'Not Available';
                        $class = 'not-available';
                    }

                    echo '<tr><td>' . $timeSlot . '</td><td class="' . $class . '">';

                    if ($status === 'Available' && !$isPastTimeSlot) {
                        echo '<span class="availability-status">' . $status . '</span>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="ground_id" value="' . $ground_id . '">
                            <input type="hidden" name="selectedDate" value="' . $selectedDate . '">
                            <input type="text" name="payment" placeholder="Enter payment amount" required>
                            <input type="hidden" name="selectedTimeSlot" value="' . $timeSlot . '">
                            <button type="submit" class="btn-pay" name="bookNow">Book Now</button>
                        </form>';
                    } else {
                        echo '<span class="availability-status">' . $status . '</span>';
                    }

                    echo '</td></tr>';
                }

                if (isset($_POST['bookNow'])) {
                    $ground_id = $_POST['ground_id'];
                    $selectedDate = $_POST['selectedDate'];
                    $selectedTimeSlot = $_POST['selectedTimeSlot'];
                    $payment = $_POST['payment'];

                    $insertQuery = "INSERT INTO booking (ground_id, booking_date, booking_time, payment,status)
                            VALUES ('$ground_id', '$selectedDate', '$selectedTimeSlot', '$payment', 'Verified')";
                    $insertResult = mysqli_query($con, $insertQuery);

                    if ($insertResult) {
                        echo '<script>alert("Booking done successfully")</script>';
                        echo '<meta http-equiv="refresh" content="0">';
                    } else {
                        echo '<script>alert("Booking failed. Error: ' . mysqli_error($con) . '");</script>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>