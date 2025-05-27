<?php
session_start();
include '../dboperation.php';
$con = new dboperation();
// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('Location: ../guest/login.php');
    exit();
}
if (!isset($_POST['receiver_id']) || empty($_POST['receiver_id'])) {
    echo json_encode(['success' => false, 'message' => 'Receiver ID is required']);
    exit();
}
$sender_id = $_SESSION['userid'];
$receiver_id = $_POST['receiver_id'];

$sql = "DELETE FROM private_chat_messages WHERE (from_user_id = '$sender_id' AND to_user_id = '$receiver_id') OR (from_user_id = '$receiver_id' AND to_user_id = '$sender_id')";
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