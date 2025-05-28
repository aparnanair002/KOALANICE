<?php
session_start();
include '../dboperation.php';
$con = new dboperation();
if (!isset($_SESSION['adminid']) || empty($_SESSION['adminid'])) {
  // Return empty or error JSON
  header('Location: ../guest/login.php');
  exit();
}

if (isset($_POST['id'])) {
    $issue_id = $_POST['id'];
    $sql="DELETE FROM report_issue WHERE issue_id = '" . $issue_id . "'";


    $stmt= $con->executequery($sql);
 if ($stmt) {
        echo json_encode(['success' => true, 'message' => 'Issue deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete issue']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid issue ID']);
}

?>