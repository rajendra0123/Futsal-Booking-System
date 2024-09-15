<?php
include 'conn.php';

// Insert the admin user with the hashed password
$password = 'admin';
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO admin_users (username, password) VALUES ('admin', '$hash')";
mysqli_query($con, $sql);
echo "$password";