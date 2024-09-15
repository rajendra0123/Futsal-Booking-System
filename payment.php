<?php
//session_name("player_session");
session_start();
// Retrieve the selected date and time slot 
$selectedDate = $_GET['selectedDate'];
$selectedTimeSlot = $_GET['selectedTimeSlot'];

$bookingTime = date('H:i:s', strtotime($selectedTimeSlot));

// Retrieve the selected ground ID 
$ground_id = $_GET['ground_id'];

include 'conn.php';
if (!isset($_SESSION['player_id'])) {
    // Redirect the user to the login page
    header("Location: login.php");
    exit;
}
$player_id = $_SESSION['player_id'];
// $owner_id = $_SESSION['owner_id'];
// Fetch the ground details from the database based on the selected ground ID
$query = "SELECT * FROM ground WHERE ground_id = $ground_id";
$result = mysqli_query($con, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $owner_id = $row['owner_id'];
    $ground_name = $row['ground_name'];
    $ground_location = $row['ground_location'];
    $ground_description = $row['ground_description'];
    $qr_code = $row['qr_code'];
    $ground_image = $row['ground_image'];
    $amount = $row['amount'];
}


if (isset($_POST['confirm'])) {
    $sc = $_FILES['sc'];

    //file extension validity
    $isValid = true;

    //for sc
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

    $sc_name = $_FILES['sc']['name'];
    $sc_tmp = $_FILES['sc']['tmp_name'];
    $sc_folder = "paymentsc/" . $sc_name;

    $fileExtension = strtolower(pathinfo($sc_name, PATHINFO_EXTENSION));


    if (!in_array($fileExtension, $allowedExtensions)) {
        echo '<script>alert("Only JPG, JPEG, PNG,GIF and Jfif files are allowed")</script>';
        $isValid = false;
    } else {
        move_uploaded_file($sc_tmp, $sc_folder);

    }

    if ($isValid) {
        // Insert the booking details into the database
        $insertQuery = "INSERT INTO booking (ground_id, booking_date, booking_time, player_id, payment,status, owner_id)
                        VALUES ('$ground_id', '$selectedDate', '$bookingTime', '$player_id', '$sc_folder','pending', '$owner_id')";
        $insertResult = mysqli_query($con, $insertQuery);

        if ($insertResult) {
            // Show the confirmation alert
            echo '<script>alert("Your booking has been sent to be confirmed.");</script>';
            echo '<script>window.location.href = "playerhomepage.php";</script>';
        } else {
            // Handle the case when the insertion fails
            echo '<script>alert("Booking failed. Error: ' . mysqli_error($con) . '");</script>';
            echo '<script>window.location.href = "playerhomepage.php";</script>';
        }
    }

}
?>

<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            text-align: center;
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .table {
            margin-top: 50px;
            border-collapse: collapse;
            width: 80%;
            background-color: white;
            padding: 20px;
        }

        h2 {
            color: #333333;
            text-align: left;
            margin-left: 80px;
            text-decoration: underline;
        }

        .text-container {
            text-align: left;
            margin-top: 30px;
            margin-left: 80px;
        }

        .text-container p {
            display: inline;
            margin: 0;
            text-align: left;
        }

        .qr-code-container {
            text-align: right;
            margin-top: -290px;
            margin-left: auto;
            margin-right: 120px;
        }

        img.qr-code {
            height: 220px;
        }

        .payment-upload {
            text-align: right;
            margin-top: 10px;
            margin-right: 120px;
        }

        input[type="file"] {
            margin-top: 10px;
            margin-right: -10px;
            cursor: pointer;
        }

        button.confirm-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: blue;
            color: white;
            /* border: none; */
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        button.confirm-button:hover {
            background-color: black;
        }
    </style>
</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <table align="center" class="table">
            <tr>
                <td colspan="2">
                    <h2>Booking Details</h2>
                    <p class="text-container">Futsal Name:
                        <?php echo $ground_name; ?>
                    </p>
                    <p class="text-container">Location:
                        <?php echo $ground_location; ?>
                    </p>
                    <p class="text-container">Date:
                        <?php echo $selectedDate; ?>
                    </p>
                    <p class="text-container">Time:
                        <?php echo $selectedTimeSlot; ?>
                    </p>
                    <p class="text-container">Amount to be paid:
                        <?php echo $amount; ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="qr-code-container">
                        <p>Make Payment via QR Code:</p>
                        <img src="<?php echo $qr_code; ?>" class="qr-code" alt="QR Code">
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="payment-upload">
                        <p>Upload payment Screenshot here:<br>
                            (JPG, JPEG, PNG,GIF and Jfif files allowed)
                        </p>
                        <input type="file" name="sc" value="sc" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" name="confirm" class="confirm-button">Confirm Booking</button>
                </td>
            </tr>
        </table>
    </form>
</body>

</html> 