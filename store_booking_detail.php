<?php
session_start();

if (isset($_POST['ground_id']) && isset($_POST['selectedDate']) && isset($_POST['selectedTimeSlot'])) {
    $_SESSION['ground_id'] = $_POST['ground_id'];
    $_SESSION['selectedDate'] = $_POST['selectedDate'];
    $_SESSION['selectedTimeSlot'] = $_POST['selectedTimeSlot'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}
