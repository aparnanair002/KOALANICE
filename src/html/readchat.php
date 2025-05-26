<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

include './dboperation.php';
$con = new dboperation();

$userid = $_SESSION['userid'];
$rec_id = $_POST['id'];  // from POST, unparameterized

if (empty($userid) || empty($rec_id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing sender or receiver.'
    ]);
    exit();
}

// ✅ Unparameterized query to update message status
$sql = "UPDATE private_chat_messages 
        SET message_status = 1 
        WHERE to_user_id = $userid AND from_user_id = $rec_id";

$result = $con->executequery($sql);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Messages marked as read.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update message status.'
    ]);
}
