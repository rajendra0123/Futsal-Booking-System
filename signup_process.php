<?php include 'conn.php'; ?>
<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if email or contact already exists
    $checkQuery = "SELECT * FROM player WHERE email='$email' OR contact='$contact' 
                   UNION 
                   SELECT * FROM owner WHERE email='$email' OR contact='$contact'";
    $result = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        echo 'exists';
    } else {
        if ($role == 'player') {
            $sql = "INSERT INTO player (fullname, email, contact, password) VALUES ('$fullname', '$email', '$contact', '$hash')";
        } else if ($role == 'owner') {
            $sql = "INSERT INTO owner (fullname, email, contact, password) VALUES ('$fullname', '$email', '$contact', '$hash')";
        }

        if ($con->query($sql) === TRUE) {
            echo 'success';
        } else {

            echo 'Error inserting data: ' . mysqli_error($con);

        }
    }
}
