<!DOCTYPE html>
<html>
<?php
include 'conn.php';
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
} else {
    $loggedin = false;
}
function generateReceiptNumber()
{
    $receiptNumber = rand(1000, 9999);

    return $receiptNumber;
}


?>


<head>
    <style>
        .navigation {
            display: flex;
            align-items: center;
        }

        .navigation a {
            margin-top: 50px;
            text-decoration: none;
            color: black;
            font-size: larger;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        .receipt-container {
            margin-top: 50px;
            max-width: 500px;
            margin-left: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: Arial, sans-serif;
        }

        .receipt-header {
            margin-top: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-title {
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .receipt-details {
            margin-bottom: 20px;
        }

        .receipt-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        .receipt-value {
            display: inline-block;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>

</head>
<div class="navigation">
    <a href="playerhomepage.php">HOME</a>
</div>

<body>
    <?php
    if (isset($_POST['receipt'])) {
        $ground_name = $_POST['ground_name'];
        $email = $_POST['email'];
        $booking_date = $_POST['booking_date'];
        $booking_time = $_POST['booking_time'];
        $ground_location = $_POST['ground_location'];
        $contact = $_POST['contact'];
        $amount = $_POST['amount'];
        $receipt = generateReceiptNumber();
        echo '
            <div class="receipt-container">
        <div class="receipt-header">
            <div class="receipt-title">Receipt Details</div>
        </div>
        <div class="receipt-details">
        <div class="receipt-label">Receipt Number:</div>
        <div class="receipt-value"> ' . $receipt . '</div>
    </div>
        <div class="receipt-details">
            <div class="receipt-label">Futsal Name:</div>
            <div class="receipt-value"> ' . $ground_name . '</div>
        </div>
        <div class="receipt-details">
        <div class="receipt-label">Player Email:</div>
        <div class="receipt-value"> ' . $email . '</div>
    </div>
        <div class="receipt-details">
            <div class="receipt-label">Booking Date:</div>
            <div class="receipt-value">' . $booking_date . '</div>
        </div>
        <div class="receipt-details">
            <div class="receipt-label">Booking Time:</div>
            <div class="receipt-value">' . $booking_time . '</div>
        </div>
        <div class="receipt-details">
            <div class="receipt-label">Ground Location:</div>
            <div class="receipt-value">' . $ground_location . '</div>
        </div>
        <div class="receipt-details">
            <div class="receipt-label">Contact:</div>
            <div class="receipt-value">' . $contact . '</div>
        </div>
        <div class="receipt-details">
            <div class="receipt-label">Total Amount:</div>
            <div class="receipt-value">' . $amount . '</div>
        </div>
        <div class="receipt-footer">
            Thank you for your booking! Kindly Screenshot it.
        </div>
    </div>';
    }
    ?>
</body>

</html>