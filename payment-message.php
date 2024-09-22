<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['ground_id'], $_SESSION['player_id'], $_SESSION['selectedDate'], $_SESSION['selectedTimeSlot'])) {
    die("Required session variables are not set.");
}

$ground_id = $_SESSION['ground_id'];
$query = "SELECT owner_id FROM ground WHERE ground_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $ground_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$pidx = $_GET['pidx'] ?? null;


if ($pidx) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/lookup/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
        CURLOPT_HTTPHEADER => array(
            'Authorization: key 66410571274c494c8df5fb09c9081ac1', // Your Khalti live key
            'Content-Type: application/json',
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    if ($response) {
        $responseArray = json_decode($response, true);
        switch ($responseArray['status']) {
            case 'Completed':
                // Payment successful
                $player_id = $_SESSION['player_id'];
                $selectedDate = $_SESSION['selectedDate'];
                $selectedTimeSlot = urldecode($_SESSION['selectedTimeSlot']);
                $owner_id = $row['owner_id'];
                $payment = 200;


                // Insert booking into the database
                $query = "INSERT INTO booking (ground_id, booking_date, booking_time, player_id, payment, status, owner_id) 
                VALUES (?, ?, ?, ?, ?, 'Pending', ?)";
                $stmt = $con->prepare($query);
                $stmt->bind_param("issisi", $ground_id, $selectedDate, $selectedTimeSlot, $player_id, $payment, $owner_id);


                if ($stmt->execute()) {
                    $_SESSION['transaction_msg'] = '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Transaction successful.",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
                break;

            case 'Expired':
            case 'User canceled':
            default:
                $_SESSION['transaction_msg'] = '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Transaction failed.",
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>';
                break;
        }
    }
} else {
    echo "pidx is missing.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    if (isset($_SESSION['transaction_msg'])) {
        echo $_SESSION['transaction_msg'];
        unset($_SESSION['transaction_msg']);
    }
    ?>

    <div class="mt-5 d-flex justify-content-center">
        <div class="mb-3">
            <img src="payment-success.jpg" class="img-fluid" alt="">
            <div class="card">
                <div class="card-body text-white bg-success">
                    <h5 class="card-title">Dear Player,</h5>
                    <p class="card-text">
                        Your Payment status is being processed. Thank you for Booking!
                    </p>
                </div>
                <div class="card-footer">
                    <a href="mybooking.php" class="btn btn-primary">See Booking Detail</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>