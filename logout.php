<?php
session_start(); // Start the session

// Check if owner is logged in
if (isset($_SESSION['owner_id'])) {
    unset($_SESSION['owner_id']);
    unset($_SESSION['email']);
    // session_destroy();
}

// Check if player is logged in
if (isset($_SESSION['player_id'])) {
    unset($_SESSION['player_id']);
    unset($_SESSION['email']);
    // session_destroy();
}

header("Location: homepage.php");
exit;