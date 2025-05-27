<?php
session_start();
ob_clean();
include '../dboperation.php';
$con = new dboperation();

// Require login
if (!isset($_SESSION['userid'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    header('Location: ../guest/login.php');
    exit();
}

// Only allow POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Set content type
header('Content-Type: application/json');

$userid = $_SESSION['userid'];
$action = $_POST['action'] ?? '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$type = $_POST['type'] ?? '';

if ($type == 'friendrequest') {



    if (!$id || !in_array($action, ['accept_request', 'reject_request', 'block'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
        exit();
    }


    // Process action
    switch ($action) {
        case 'accept_request':
            $stmt = "UPDATE tbl_requests SET status = 'accepted' WHERE req_id = '$id'";
            break;
        case 'reject_request':
            $stmt = "UPDATE tbl_requests SET status = 'rejected' WHERE req_id = '$id'";
            break;
        case 'block':
            $stmt = "UPDATE tbl_requests SET status = 'blocked' WHERE req_id = '$id'";
            break;
    }

    $success = $con->executequery($stmt);

    if ($success==1) {
        echo json_encode(['success' => true, 'message' => ucfirst(str_replace('_', ' ', $action)) . ' successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
}
if ($type == 'grprequest') {



    if (!$id || !in_array($action, ['accept_request', 'reject_request', 'block'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
        exit();
    }


    // Process action
    switch ($action) {
        case 'accept_request':
            $stmt = "UPDATE group_members SET status = 'Added' WHERE member_id = '$id'";
            break;
        case 'reject_request':
            $stmt = "UPDATE group_members SET status = 'rejected' WHERE member_id = '$id'";
            break;
        case 'block':
            $stmt = "UPDATE group_members SET status = 'blocked' WHERE member_id = '$id'";
            break;
    }

    $success = $con->executequery($stmt);

    if ($success==1) {
        echo json_encode(['success' => true, 'message' => ucfirst(str_replace('_', ' ', $action)) . ' successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
}