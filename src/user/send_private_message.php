<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

$sender_id = $_SESSION['userid'];
$receiver_id = $_POST['receiver_id'];
$message = trim($_POST['message']);

if (empty($sender_id) || empty($receiver_id) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing sender, receiver, or message content.'
    ]);
        header('Location: ../guest/login.php');

    exit();
}

// Escape special characters for safety (minimal injection prevention)
$sender_id = $con->con->real_escape_string($sender_id);
$receiver_id = $con->con->real_escape_string($receiver_id);
$message = $con->con->real_escape_string($message);

// UNPARAMETERIZED SQL query
$sql = "INSERT INTO private_chat_messages (from_user_id, to_user_id, content,timestamp) 
        VALUES ('$sender_id', '$receiver_id', '$message',Now())";

$res = $con->executequery($sql);

if ($res) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Message sent successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to send message: ' . mysqli_error($con->con)
    ]);
}
?>
