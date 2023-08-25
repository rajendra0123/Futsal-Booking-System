<?php
include 'conn.php';

// Insert the admin user with the hashed password
$password = 'enteradminpage';
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO admin_users (username, password) VALUES ('rajendra_c0', '$hash')";
mysqli_query($con, $sql);
echo "$password";
?>