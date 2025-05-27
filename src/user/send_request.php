<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

// Redirect if user not logged in or receiver ID is missing
if (!isset($_SESSION['userid']) || empty($_SESSION['userid']) || !isset($_GET['reciever_id'])) {
    header('Location: ../guest/login.php');
    exit();
}

$reciever_id = $_GET['reciever_id'];
$sender_id = $_SESSION['userid'];

// Escape inputs for safety
$sender_id = $con->con->real_escape_string($sender_id);
$reciever_id = $con->con->real_escape_string($reciever_id);

// Check if the request already exists (UNPARAMETERIZED)
$check_sql = "SELECT * FROM tbl_requests WHERE sender_id = '$sender_id' AND reciever_id = '$reciever_id'";
$check_stmt = $con->executequery($check_sql);

if (mysqli_num_rows($check_stmt) > 0) {
    echo json_encode(['success' => false, 'message' => 'Request already sent.']);
    exit();
}

// Insert new request if not exists (UNPARAMETERIZED)
$insert_sql = "INSERT INTO tbl_requests (sender_id, reciever_id) VALUES ('$sender_id', '$reciever_id')";
$insertqry = $con->executequery($insert_sql);

if ($insertqry) {
    echo json_encode(['success' => true, 'message' => 'Request Sent Successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send request']);
}
?>
