<?php
session_start();
include './dboperation.php';

$con = new dboperation();

if (!isset($_SESSION['userid'], $_POST['group_id'], $_POST['message'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required data'
    ]);
    exit();
}

$sender_id = $_SESSION['userid'];
$group_id = trim($_POST['group_id']);
$message = trim($_POST['message']);

if ($sender_id === '' || $group_id === '' || $message === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Sender ID, Group ID, and Message are required.'
    ]);
    exit();
}

// Escape inputs to avoid breaking the SQL
$group_id = $con->con->real_escape_string($group_id);
$sender_id = $con->con->real_escape_string($sender_id);
$message = $con->con->real_escape_string($message);

// UNPARAMETERIZED SQL
$sql = "INSERT INTO messages (group_id, user_id, message,sent_at) VALUES ('$group_id', '$sender_id', '$message',Now())";
$result = $con->executequery($sql);

if ($result) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Message sent successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Message failed: ' . mysqli_error($con->con)
    ]);
}

$con->con->close();
?>
