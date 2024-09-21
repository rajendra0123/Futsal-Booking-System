<?php
session_start();
include 'conn.php'; // Include your database connection file

$ground_id = $_SESSION['ground_id'];
$query = "SELECT owner_id FROM ground WHERE ground_id = $ground_id";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

// Get the pidx from the URL
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

                $ground_id = $_SESSION['ground_id'];
                $player_id = $_SESSION['player_id'];
                $selectedDate = $_SESSION['selectedDate'];
                $selectedTimeSlot = $_SESSION['selectedTimeSlot'];
                $owner_id = $row['owner_id'];
                $payment = 200;

                // Insert the booking details into the database
                $query = "INSERT INTO booking (ground_id, booking_date, booking_time, player_id, payment, status, owner_id) 
                          VALUES (?, ?, ?, ?, ?, 'Pending', ?)";

                $stmt = $con->prepare($query);
                $stmt->bind_param("issiii", $ground_id, $selectedDate, $selectedTimeSlot, $player_id, $payment, $owner_id);

                if ($stmt->execute()) {
                    // Booking inserted successfully
                    $_SESSION['transaction_msg'] = '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Transaction successful.",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';

                    // Redirect to the success message page
                    header("Location: payment-message.php");
                    exit();
                } else {
                    // Failed to insert booking
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
                break;

            case 'Expired':
            case 'User canceled':
                // Update the session with a failure message
                $_SESSION['transaction_msg'] = '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Transaction failed.",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';

                // Redirect to the time slot page
                header("Location: timeslot.php");
                exit();

            default:
                // Default case for failed transactions
                $_SESSION['transaction_msg'] = '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Transaction failed.",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';

                header("Location: timeslot.php");
                exit();
        }
    }
}
