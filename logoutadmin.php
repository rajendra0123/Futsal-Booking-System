<?php
session_start();

// Clear the session data
session_unset();
session_destroy();

// Redirect the user to the homepage
header("Location: adminlogin.php");
exit;
?>