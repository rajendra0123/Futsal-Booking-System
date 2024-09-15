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
                margin-top: 20px;
            }

            .sidebar {
                width: 20%;
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .sidebar a {
                display: block;
                text-decoration: none;
                color: #343a40;
                font-weight: bold;
                padding: 10px 0;
                border-bottom: 1px solid #e9ecef;
                transition: color 0.3s ease;
            }

            .sidebar a:hover {
                color: #007bff;
            }

            .container {
                display: flex;
                margin-left: 300px;
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

            /* .hidden {
                display: none;
            } */

            .content {
                margin-top: 80px;
                margin-left: 50px;
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
                <table id="table-owner" class="hidden">
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
                         g.ground_name, b.booking_date, b.booking_time,p.fullname
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