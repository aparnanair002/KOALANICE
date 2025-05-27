<?php
session_start();
header('Content-Type: application/json');
include '../dboperation.php';

$con = new dboperation();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
        header('Location: ../guest/login.php');

    exit();
}

$group_id = $_POST['group_id'] ?? null;
$user_id = $_POST['user_id'] ?? null;

if (empty($group_id) || empty($user_id)) {
    echo json_encode(['success' => false, 'message' => 'Missing group_id or user_id']);
    exit();
}

$status = "Requested";

$stmt = "INSERT INTO group_members (group_id, user_id, status) VALUES ('$group_id', '$user_id', '$status')";
$res = $con->executequery($stmt);

if (!$res) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($con->con)]);
    exit();
} else {
    echo json_encode(['success' => true, 'message' => 'Member added successfully']);
    exit();
}
?>
