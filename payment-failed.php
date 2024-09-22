<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION['transaction_msg'])) {
        echo '<script>Swal.fire({ icon: "error", title: "' . $_SESSION['transaction_msg'] . '", showConfirmButton: false, timer: 1500 });</script>';
        unset($_SESSION['transaction_msg']);
    }
    ?>
    <div class="mt-5 d-flex justify-content-center">
        <div class="mb-3">
            <div class="card">
                <div class="card-body text-white bg-danger">
                    <h5 class="card-title">Transaction Failed</h5>
                    <p class="card-text">Please try again later or contact support.</p>
                </div>
                <div class="card-footer">
                    <a href="groundlist.php" class="btn btn-primary">Try Again</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>