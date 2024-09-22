<!DOCTYPE html>
<html>
<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['player_id']) || $_SESSION['loggedin'] !== true) {
    header('Location: homepage.php');
    exit;
}

function generateReceiptNumber()
{
    return rand(1000, 9999);
}

if (isset($_POST['receipt'])) {
    require('fpdf.php'); // Include the FPDF library

    $ground_id = $_SESSION['ground_id'];
    $player_id = $_SESSION['player_id'];

    // Fetch ground details
    $groundQuery = "SELECT ground_name, amount, ground_location, futsal_logo, contact FROM ground WHERE ground_id = ?";
    $stmt = $con->prepare($groundQuery);
    $stmt->bind_param("i", $ground_id);
    $stmt->execute();
    $groundResult = $stmt->get_result();
    $ground = $groundResult->fetch_assoc();

    // Fetch booking details
    $bookingQuery = "SELECT payment FROM booking WHERE player_id = ? AND ground_id = ? ORDER BY booking_id DESC LIMIT 1";
    $stmt = $con->prepare($bookingQuery);
    $stmt->bind_param("ii", $player_id, $ground_id);
    $stmt->execute();
    $bookingResult = $stmt->get_result();
    $booking = $bookingResult->fetch_assoc();

    $advance_payment = $booking['payment'] ?? 0;
    $total_amount = $ground['amount'] ?? 0;
    $remaining_payable = $total_amount - $advance_payment;

    $receipt = generateReceiptNumber();
    // Create a PDF document
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $logoPath = htmlspecialchars($ground['futsal_logo']);
    $pdf->Image($logoPath, 75, 40, 130);

    $pdf->Cell(0, 10, 'RECEIPT DETAILS', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Receipt Number: ' . $receipt, 0, 1);
    $pdf->Cell(0, 10, 'Futsal Name: ' . htmlspecialchars($ground['ground_name']), 0, 1);
    $pdf->Cell(0, 10, 'Player Email: ' . htmlspecialchars($_SESSION['email']), 0, 1);
    $pdf->Cell(0, 10, 'Booking Date: ' . htmlspecialchars($_POST['booking_date']), 0, 1);
    $pdf->Cell(0, 10, 'Booking Time: ' . htmlspecialchars($_POST['booking_time']), 0, 1);
    $pdf->Cell(0, 10, 'Ground Location: ' . htmlspecialchars($ground['ground_location']), 0, 1);
    $pdf->Cell(0, 10, 'Contact: ' . htmlspecialchars($ground['contact']), 0, 1);
    $pdf->Cell(0, 10, 'Total Amount: ' . htmlspecialchars($total_amount), 0, 1);
    $pdf->Cell(0, 10, 'Advance Payment: ' . htmlspecialchars($advance_payment), 0, 1);
    $pdf->Cell(0, 10, 'Remaining Payable: ' . htmlspecialchars($remaining_payable), 0, 1);
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Thank you for your booking!', 0, 1, 'C');

    // Output the PDF
    ob_end_clean(); // Clean the output buffer
    $pdf->Output('D', 'Receipt_' . $receipt . '.pdf'); // Force download the PDF
    exit; // Stop further execution

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
    </style>
</head>

<body>
    <div class="navigation">
        <a href="playerhomepage.php">HOME</a>
    </div>
    <form method="POST">
        <input type="hidden" name="receipt" value="receipt">
        <input type="submit" value="Download Receipt">
    </form>
</body>

</html>