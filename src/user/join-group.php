<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

// Redirect if user not logged in or receiver ID is missing
if (!isset($_SESSION['userid']) || empty($_SESSION['userid']) || !isset($_GET['grpid'])) {
    header('Location: ../guest/login.php');
    exit();
}

$user_id = $_SESSION['userid'];
$grpid = $_GET['grpid'];


$insert_sql = "INSERT INTO group_members (user_id, group_id,status) VALUES (?, ?,?)";
$status="Added";
$insert_stmt=$con->executequery($insert_sql, $user_id, $grpid, $status);


if ($insert_stmt) {
    echo json_encode(['success' => true, 'message' => 'Request Sent Successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send request']);
}
?>
