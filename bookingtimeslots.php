<?php
include 'conn.php';
session_name("owner_session");
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
<html>

<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .available {
            color: green;
        }

        .booked {
            color: red;
        }

        .btn-pay {
            background-color: green;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
    <title>Time Slots</title>
</head>

<body>
    <h1 align="center">Time Slots for
        <?php echo $selectedDate; ?>
    </h1>

    <table>
        <tr>
            <th>Time Slot</th>
            <th>Status</th>
        </tr>
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

            } elseif ($isPastTimeSlot) {
                $status = 'Not Available';

            }

            echo '<tr><td>' . $timeSlot . '</td><td class="' . $class . '">';

            if ($status === 'Available' && !$isPastTimeSlot) {
                echo '<span class="availability-status">' . $status . '</span>
                <form method="POST" style="display: inline-block;">
                    <input type="hidden" name="ground_id" value="' . $ground_id . '">
                    <input type="hidden" name="selectedDate" value="' . $selectedDate . '">
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

            $insertQuery = "INSERT INTO booking (ground_id, booking_date, booking_time, status)
                    VALUES ('$ground_id', '$selectedDate', '$selectedTimeSlot', 'Verified')";
            $insertResult = mysqli_query($con, $insertQuery);

            if ($insertResult) {
                echo '<script>alert("Booking done successfully")</script>';
                echo '<meta http-equiv="refresh" content="0">';
                $status = 'Booked';
            } else {
                echo '<script>alert("Booking failed. Error: ' . mysqli_error($con) . '");</script>';
            }
        }
        ?>
    </table>
</body>

</html>