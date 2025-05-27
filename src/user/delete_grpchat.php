<?php
session_start();
include '../dboperation.php';
$con = new dboperation();
// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('Location: ../guest/login.php');
    exit();
}
if (!isset($_POST['group_id']) || empty($_POST['group_id'])) {
    echo json_encode(['success' => false, 'message' => 'Receiver ID is required']);
    exit();
}
$group_id = $_POST['group_id'];

$sql = "DELETE FROM messages WHERE group_id = '$group_id'";
$res=$con->executequery($sql);
if ($res != 1) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete conversation']);
    exit();
}
else{
echo json_encode(['success' => true, 'message' => 'Conversation deleted successfully']);
}
$con->con->close();
?>