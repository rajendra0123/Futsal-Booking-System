<?php
$con = mysqli_connect("localhost", "root", "", "futsal");
if (!$con) {
    die("Error" . mysqli_connect_error());
}
