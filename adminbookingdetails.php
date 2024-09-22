<!DOCTYPE html>
<html>

<head>
    <html>
    <?php
    include 'conn.php';
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('Location: adminlogin.php');
        exit;
    }
    ?>

    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 0;
            }

            header {
                background-color: #343a40;
                color: #fff;
                padding: 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .title {
                display: flex;
                align-items: center;
            }

            .title h1 {
                margin: 0;
                font-size: 40px;
            }

            .title a {
                text-decoration: none;
                color: #fff;
                margin-left: 30px;
                font-weight: bold;
                font-size: 20px;
            }

            .right a {
                text-decoration: none;
                color: #fff;
                font-weight: bold;
                padding: 10px 20px;
                background-color: #dc3545;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .right a:hover {
                background-color: #c82333;
            }

            .container {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }

            .content {
                width: 90%;
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
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 15px 20px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #4CAF50;
                color: white;
                font-size: 18px;
            }

            tr:hover {
                background-color: #f1f1f1;
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
        <div class="title">
            <h1>FUTSOL</h1>
            <a href="adminpage.php">HOME</a>
        </div>
    </header>

<body>
    <?php
    // Check if the admin is logged in
    if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] == true) {
        ?>
        <div class="container">
            <div class="content">
                <!-- Owner table -->
                <h3 align="center">Booking Details</h3>
                <table id="table-owner">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Player Name</th>
                            <th>Futsal Name</th>
                            <th>Booking Date</th>
                            <th>Booking Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT b.booking_id, b.ground_id, b.player_id,
                         g.ground_name, b.booking_date, b.booking_time, p.fullname
                         FROM booking b
                         JOIN ground g ON b.ground_id = g.ground_id
                         JOIN player p ON b.player_id = p.player_id";

                        $result = mysqli_query($con, $sql);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $bookingID = $row['booking_id'];
                                $fullname = $row['fullname'];
                                $futsalName = $row['ground_name'];
                                $bookingDate = $row['booking_date'];
                                $bookingTime = $row['booking_time'];

                                echo '
                                <tr>
                                    <td>' . $bookingID . '</td>
                                    <td>' . $fullname . '</td>
                                    <td>' . $futsalName . '</td>
                                    <td>' . $bookingDate . '</td>
                                    <td>' . $bookingTime . '</td>
                                </tr>';
                            }
                        } else {
                            // Display an error message if the query execution fails
                            echo "Error: " . mysqli_error($con);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    ?>
</body>

</html>