<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    header('Location: ../guest/login.php');
    exit();}

$requestId = $_POST['id'] ?? null;

if ($requestId) {
    $sql = "DELETE FROM tbl_requests WHERE req_id = '$requestId'";
    $stmt = $con->executequery($sql);
    if ($stmt == 1) {

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Request ID is required']);
}
