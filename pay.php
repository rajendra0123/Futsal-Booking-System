<?php
session_start();
include 'conn.php';
//include 'nav.php';


if (!isset($_SESSION['player_id'])) {
    die('Player ID not found in session.');
}

$player_id = $_SESSION['player_id'];


// Fetch player information from the database
$query = "SELECT fullname, email, contact FROM player WHERE player_id = $player_id";
$result = mysqli_query($con, $query);
$player = mysqli_fetch_assoc($result);

if (!isset($_SESSION['ground_id'], $_SESSION['selectedDate'], $_SESSION['selectedTimeSlot'])) {
    die('Booking details not found in session.');
}

$ground_id = $_SESSION['ground_id'];
$selectedDate = $_SESSION['selectedDate'];
$selectedTimeSlot = $_SESSION['selectedTimeSlot'];
$amount = 20000; // Amount in paisa (so it's 200.00)


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Prepare the data for Khalti API
    $postFields = array(
        "return_url" => "http://localhost/futsalfinder/payment-message.php",
        "website_url" => "http://localhost/futsalfinder/",
        "amount" => $amount,
        "purchase_order_id" => $ground_id,
        "purchase_order_name" => 'Futsal Booking',
        "customer_info" => array(
            "name" => $player['fullname'],
            "email" => $player['email'],
            "phone" => $player['contact']
        )
    );

    // Convert the data to JSON format
    $jsonData = json_encode($postFields);

    // Initialize cURL and set options
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Authorization: key 66410571274c494c8df5fb09c9081ac1', // Replace with your actual live/public key if needed
            'Content-Type: application/json',
        ),
    ));

    // Execute the request and capture the response
    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo json_encode(['success' => false, 'message' => curl_error($curl)]);
    } else {
        $responseArray = json_decode($response, true);

        // Check if the response contains an error or the payment URL
        if (isset($responseArray['error'])) {
            echo json_encode(['success' => false, 'message' => $responseArray['error']]);
        } elseif (isset($responseArray['payment_url'])) {
            if (isset($responseArray['pidx'])) {

                header('Location: ' . $responseArray['payment_url']);

            }
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Unexpected response from Khalti']);
        }
    }
    curl_close($curl);

}
