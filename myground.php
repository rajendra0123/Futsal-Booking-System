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

// Fetch total bookings and revenue data
$query = "SELECT g.ground_name, COUNT(b.booking_id) AS total_bookings, SUM(b.payment) AS total_revenue 
FROM `ground` g
LEFT JOIN `booking` b ON g.ground_id = b.ground_id AND b.status = 'Verified'
WHERE g.owner_id = '$owner_id'
GROUP BY g.ground_id;"
;
$result = mysqli_query($con, $query);

$grounds = [];
$bookings = [];
$revenue = [];

while ($row = mysqli_fetch_assoc($result)) {
    $grounds[] = $row['ground_name'];
    $bookings[] = (int) $row['total_bookings'];
    $revenue[] = (float) $row['total_revenue'];
}

$grounds = json_encode($grounds);
$bookings = json_encode($bookings);
$revenue = json_encode($revenue);
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

        .chart-container {
            margin-right: 30px;
            width: 40%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .chart-container h3 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        canvas {
            width: 100% !important;
            height: 350px !important;
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

        <!-- Right side chart section -->
        <div class="chart-container">
            <h3>Bookings and Revenue</h3>
            <canvas id="booking-chart"></canvas>
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

        // Chart.js implementation
        var ctx = document.getElementById('booking-chart').getContext('2d');
        var bookingChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $grounds; ?>,
                datasets: [{
                    label: 'Total Bookings',
                    data: <?php echo $bookings; ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y1',
                }, {
                    label: 'Total Revenue',
                    data: <?php echo $revenue; ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    yAxisID: 'y2',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y1: {
                        beginAtZero: true,
                        position: 'left',
                        ticks: {
                            stepSize: 2,
                        },
                        title: {
                            display: true,
                            text: 'Total Bookings'
                        }
                    },
                    y2: {
                        beginAtZero: true,
                        position: 'right',
                        ticks: {

                        },
                        title: {
                            display: true,
                            text: 'Total Revenue'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: false

                    }
                }
            }
        });

    </script>

</body>

<?php include 'footer.php'; ?>

</html>