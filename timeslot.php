<?php
include 'conn.php';

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
$query = "SELECT booking_time,status FROM booking WHERE ground_id = $ground_id AND booking_date = '$selectedDate'";
$result = mysqli_query($con, $query);
$bookedTimeSlots = array();
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookedTimeSlots[] = $row;
        $status = $row['status'];
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

        // Loop through each time slot
        foreach ($timeSlots as $timeSlot) {
            // Check if the selected date is the current date
            $isCurrentDate = ($selectedDate === $currentDate);

            $status = 'Available';
            if ($isCurrentDate) {
                // If the selected date is the current date, check if the time slot is in the past
                $currentTime = date('H:i:s');
                if ($selectedDate === $currentDate && $timeSlot < $currentTime) {
                    $status = 'Not Available'; // Disable booking for past time slots
                }
            }

            foreach ($bookedTimeSlots as $bookedTimeSlot) {
                if ($bookedTimeSlot['booking_time'] === $timeSlot && $bookedTimeSlot['status'] === 'Verified') {
                    $status = 'Booked';
                } else if ($bookedTimeSlot['booking_time'] === $timeSlot && $bookedTimeSlot['status'] === 'pending') {
                    $status = 'Pending';
                    break;
                }
            }

            $class = in_array($timeSlot, $bookedTimeSlots) ? 'booked' : 'available';
            $button = '';
            if (!in_array($timeSlot, $bookedTimeSlots) && $status === 'Available') {
                $button = '<a class="btn-pay" href="payment.php?ground_id=' . $ground_id . '&selectedDate=' . urlencode($selectedDate) . '&selectedTimeSlot=' . urlencode($timeSlot) . '">Book Now</a>';
            } elseif ($status === 'pending') {
                $button = ''; // Empty button for Pending status
            }

            echo "<tr><td>$timeSlot</td><td class=\"$class\">$status $button</td></tr>";
        }
        ?>
    </table>
</body>

</html>