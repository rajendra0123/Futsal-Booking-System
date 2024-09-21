<?php
session_start();
include 'conn.php';
//$_SESSION['ground_id'] = $ground_id;

$player_id = $_SESSION['player_id'];
$query = "SELECT fullname, email, contact FROM player WHERE player_id = $player_id";
$result = mysqli_query($con, $query);
$player = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ground_id = $_POST['ground_id'];
    $selectedDate = $_POST['selectedDate'];
    $selectedTimeSlot = $_POST['selectedTimeSlot'];
    $amount = 20000;


    $postFields = array(
        "return_url" => "http://localhost/futsalfinder/payment-response.php",
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

}
$jsonData = json_encode($postFields);

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
        'Authorization: key 66410571274c494c8df5fb09c9081ac1',
        'Content-Type: application/json',
    ),
));

$response = curl_exec($curl);


if (curl_errno($curl)) {
    echo 'Error:' . curl_error($curl);
} else {
    $responseArray = json_decode($response, true);

    if (isset($responseArray['error'])) {
        echo 'Error: ' . $responseArray['error'];
    } elseif (isset($responseArray['payment_url'])) {
        // Redirect the user to the payment page
        header('Location: ' . $responseArray['payment_url']);
    } else {
        echo 'Unexpected response: ' . $response;
    }
}

curl_close($curl);



