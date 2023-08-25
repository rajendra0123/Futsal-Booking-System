<?php
session_start();

if (isset($_SESSION['owner_id'])) {
    unset($_SESSION['owner_id']);
    unset($_SESSION['email']);
    // Destroy the session
    session_destroy();

}


if (isset($_SESSION['player_id'])) {
    unset($_SESSION['player_id']);
    unset($_SESSION['email']);
    // Destroy the session
    session_destroy();

}


if (isset($_SESSION['admin_id'])) {
    unset($_SESSION['admin_id']);
    // Destroy the session
    session_destroy();

}


header("Location: login.php");
exit;
?>