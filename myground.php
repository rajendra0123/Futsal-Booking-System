<!DOCTYPE html>
<html>
<?php
session_start();
include 'conn.php';
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

if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = "SELECT * FROM `owner` WHERE email='$email'";
    $result = mysqli_query($con, $query);
    if (!$result || mysqli_num_rows($result) == 0) {
        header("Location: homepage.php");
        exit();
    }
    $row = mysqli_fetch_assoc($result);
    $fullname = $row['fullname'];
    $owner_id = $row['owner_id'];
}

// Fetch total bookings and revenue data for each ground
$query = "
SELECT 
    g.ground_id, 
    g.ground_name, 
    IF(COUNT(b.booking_id) > 0, g.amount, NULL) AS amount,  
    COUNT(b.booking_id) AS total_bookings
FROM ground g
LEFT JOIN booking b ON g.ground_id = b.ground_id AND b.status = 'Verified'
WHERE g.owner_id = '$owner_id'
GROUP BY g.ground_id;";


$result = mysqli_query($con, $query);

$groundStats = [];
while ($row = mysqli_fetch_assoc($result)) {
    $groundId = $row['ground_id'];
    $groundStats[$groundId] = [
        'ground_name' => $row['ground_name'],
        'total_bookings' => (int) $row['total_bookings'],
        'total_revenue' => (float) $row['amount'],
        'monthly_bookings' => 0,
        'todays_bookings' => 0,
    ];
}

// Fetch this month's bookings for each ground
$currentMonth = date('Y-m');
$queryMonth = "
SELECT 
    ground_id, 
    COUNT(booking_id) AS monthly_bookings 
FROM booking 
WHERE ground_id IN (SELECT ground_id FROM ground WHERE owner_id = '$owner_id') 
AND booking_date LIKE '$currentMonth%' 
AND status = 'Verified' 
GROUP BY ground_id;";

$resultMonth = mysqli_query($con, $queryMonth);
while ($rowMonth = mysqli_fetch_assoc($resultMonth)) {
    $groundId = $rowMonth['ground_id'];
    if (isset($groundStats[$groundId])) {
        $groundStats[$groundId]['monthly_bookings'] = (int) $rowMonth['monthly_bookings'];
    }
}

// Fetch today's bookings for each ground
$currentDate = date('Y-m-d');
$queryToday = "
SELECT 
    ground_id, 
    COUNT(booking_id) AS todays_bookings 
FROM booking 
WHERE ground_id IN (SELECT ground_id FROM ground WHERE owner_id = '$owner_id') 
AND booking_date = '$currentDate' 
AND status = 'Verified' 
GROUP BY ground_id;";

$resultToday = mysqli_query($con, $queryToday);
while ($rowToday = mysqli_fetch_assoc($resultToday)) {
    $groundId = $rowToday['ground_id'];
    if (isset($groundStats[$groundId])) {
        $groundStats[$groundId]['todays_bookings'] = (int) $rowToday['todays_bookings'];
    }
}


?>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .left-section {
            display: flex;
            flex-direction: column;
            width: 60%;
            margin-right: 20px;
        }

        .booking-section a {
            text-decoration: none;
            color: black;
        }

        .booking-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .booking-section:hover {
            background-color: #e9ecef;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .booking-section h3 {
            margin: 0;
            font-size: 18px;

        }


        .booking-section input {
            margin-top: 10px;
            padding: 15px;
            font-size: 14px;
            border-radius: 4px;
            width: 80%;
        }

        .booking-section select {
            margin-top: 10px;
            padding: 15px;
            font-size: 14px;
            border-radius: 4px;
            width: 85%;
        }

        .booking-section button {
            margin-top: 10px;
            padding: 15px;
            font-size: 14px;
            border-radius: 4px;
            width: 30%;

        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;

        }

        .admin-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .stat-item {
            background-color: #fff;
            padding: 20px;
            width: 200px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 8px;
        }

        .stat-item h3 {
            margin-bottom: 10px;
            font-size: 16px;
            color: #333;
        }

        .stat-item p {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #4CAF50;
        }

        .stat-item p strong {
            font-size: 14px;
            font-weight: normal;
            color: #333;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <!-- Left side with three sections -->
        <div class="left-section">
            <!-- Select Ground Section -->
            <div class="booking-section">
                <h3>Select Ground</h3>
                <select id="ground-dropdown">
                    <option value="">Select Ground</option>
                    <?php
                    $query = "SELECT * FROM `ground` WHERE owner_id = '$owner_id'";
                    $result = mysqli_query($con, $query);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $ground_id = $row['ground_id'];
                            $ground_name = $row['ground_name'];
                            echo "<option value='$ground_id'>$ground_name</option>";

                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Booking Details Section -->
            <div class="booking-section">
                <a href="bookingdetails.php">
                    <h3>Booking Details</h3>
                </a>
            </div>

            <!-- Booking Time Slots Section -->
            <div class="booking-section">
                <h3>Booking Time Slots</h3>
                <div class="right-section">
                    <?php
                    $currentDate = date('Y-m-d');
                    $maxDate = date('Y-m-d', strtotime($currentDate . ' + 2 days'));
                    ?>
                    <input type="date" id="booking-date" min="<?php echo $currentDate; ?>"
                        max="<?php echo $maxDate; ?>">
                    <button id="see-time-slots">See Time Slots</button>
                </div>
            </div>
        </div>

        <div class="admin-stats">
            <?php foreach ($groundStats as $ground): ?>
                <div class="stat-item">
                    <p><strong>Total Bookings:</strong></p>
                    <p><?php echo $ground['total_bookings']; ?></p>
                </div>

                <div class="stat-item">
                    <p><strong>Total Revenue:</strong></p>
                    <p>NPR <?php echo number_format($ground['total_revenue'], 2); ?></p>
                </div>

                <div class="stat-item">
                    <p><strong>This Month's Bookings:</strong></p>
                    <p><?php echo $ground['monthly_bookings']; ?></p>
                </div>

                <div class="stat-item">
                    <p><strong>Today's Bookings:</strong></p>
                    <p><?php echo $ground['todays_bookings']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>




    </div>

    <script>
        function setGroundIdOnLoad() {
            var groundDropdown = document.getElementById("ground-dropdown");
            var selectedGround = groundDropdown.value;

            // If a ground is already selected (from previous session)
            if (selectedGround) {
                document.getElementById("see-time-slots").dataset.groundId = selectedGround;
                console.log("Ground ID set on load:", selectedGround);
            }
        }

        // Event listener for dropdown change
        document.getElementById("ground-dropdown").addEventListener("change", function () {
            var groundId = this.value;
            console.log("Selected ground ID:", groundId); // Debugging: Ensure groundId is being captured
            document.getElementById("see-time-slots").dataset.groundId = groundId; // Assign to button
        });

        // Button click to see time slots
        document.getElementById("see-time-slots").addEventListener("click", function () {
            var selectedDate = document.getElementById("booking-date").value;
            var groundId = this.dataset.groundId;

            if (groundId) {
                window.location.href = "bookingtimeslots.php?ground_id=" + groundId + "&selectedDate=" + selectedDate;
            } else {
                alert("Please select a Futsal first.");
            }
        });

        document.getElementById("ground-dropdown").addEventListener("dblclick", function () {
            var groundId = this.value;
            if (groundId) {
                window.location.href = "mygrounds.php?ground_id=" + groundId;
            }
        });

        // Check if the page was loaded using the back button (using navigation type)
        window.onload = function () {
            if (performance.navigation.type === 2) {
                // The page was loaded via the back button, so reload it
                location.reload();
            }
        };
    </script>



</body>

<?php include 'footer.php'; ?>

</html>