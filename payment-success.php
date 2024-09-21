<?php
session_start();
include 'conn.php';
print_r($_POST);
print_r($_GET);

// Get the amount sent by Khalti after the user completes payment
$amount = $_POST['amount'] ?? $_GET['amount'] ?? null;
$token = $_POST['token'] ?? $_GET['token'] ?? null;

// Retrieve ground_id and other details from the session
$ground_id = $_SESSION['ground_id'] ?? null;
$player_id = $_SESSION['player_id'] ?? null;

$args = http_build_query(array(
    'token' => $token,
    'amount' => $amount,
));

// Khalti verification API
$verification_url = "https://a.khalti.com/api/v2/payment/verify/";

print_r($data);

// Setup cURL for verification request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verification_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
    "Authorization: Key 66410571274c494c8df5fb09c9081ac1" // Replace with your actual secret key
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute the cURL request
$response = curl_exec($ch);
curl_close($ch);

// Decode the response
$res = json_decode($response, true);

// Check if the verification was successful
if (isset($res['idx'])) {
    // Payment was verified
    $khalti_idx = $res['idx'];

    // Now perform the lookup
    $lookup_url = "https://a.khalti.com/api/v2/epayment/lookup/";
    $lookup_data = json_encode(['pidx' => $res['pidx']]); // Use the pidx from the verification response

    // Setup cURL for lookup request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $lookup_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $lookup_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Key 66410571274c494c8df5fb09c9081ac1", // Replace with your actual secret key
        "Content-Type: application/json"
    ]);

    // Execute the lookup request
    $lookup_response = curl_exec($ch);
    curl_close($ch);

    // Decode the lookup response
    $lookup_res = json_decode($lookup_response, true);

    // Check the payment status from the lookup response
    if (isset($lookup_res['status'])) {
        if ($lookup_res['status'] === 'Completed') {
            // Insert booking or payment details in your database here
            $query = "INSERT INTO booking (ground_id, player_id, amount, status) VALUES (?, ?, ?, 'Verified')";
            $stmt = $con->prepare($query);
            $stmt->bind_param("iii", $ground_id, $player_id, $amount);

            if ($stmt->execute()) {
                echo "Payment verified successfully!";
            } else {
                echo "Error saving payment details: " . $stmt->error;
            }
        } else {
            echo "Payment status: " . $lookup_res['status'] . ". Service not provided.";
        }
    } else {
        echo "Payment lookup failed.";
        print_r($lookup_res); // Optional: print for debugging
    }
} else {
    echo "Payment verification failed.";
    // Optionally print $res for debugging
    print_r($res);
}
