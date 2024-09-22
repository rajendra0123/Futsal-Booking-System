<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION['transaction_msg'])) {
        echo '<script>Swal.fire({ icon: "success", title: "' . $_SESSION['transaction_msg'] . '", showConfirmButton: false, timer: 1500 });</script>';
        unset($_SESSION['transaction_msg']);
    }
    ?>
    <div class="mt-5 d-flex justify-content-center">
        <div class="mb-3">
            <img src="payment-success.jpg" class="img-fluid" alt="">
            <div class="card">
                <div class="card-body text-white bg-success">
                    <h5 class="card-title">Dear Player,</h5>
                    <p class="card-text">Your Payment status is being processed. Thank you for Booking!</p>
                </div>
                <div class="card-footer">
                    <a href="mybooking.php" class="btn btn-primary">See Booking Detail</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>