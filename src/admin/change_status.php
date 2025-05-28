<?php
session_start();
header('Content-Type: application/json');

// Include database connection
include '../dboperation.php';
$con = new dboperation();

// Check if admin is logged in
if (!isset($_SESSION['adminid']) || empty($_SESSION['adminid'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Validate POST inputs
if (!isset($_POST['user_id'], $_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit();
}

$user_id = intval($_POST['user_id']);
$status = trim($_POST['status']);

// Allow only specific statuses
$allowed_statuses = ['Accepted', 'Reject'];
if (!in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status value']);
    exit();
}

// Execute update
$sql = "UPDATE users SET user_status = '$status' WHERE id = $user_id";
$result = $con->executequery($sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
