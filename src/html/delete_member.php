<?php
session_start();
include './dboperation.php';
$con = new dboperation();

if (!isset($_POST['user_id']) || !isset($_POST['group_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit();
}

$user_id = (int)$_POST['user_id'];
$group_id = (int)$_POST['group_id'];

$sql = "DELETE FROM group_members WHERE user_id = $user_id AND group_id = $group_id";
$result = $con->executequery($sql);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Delete failed']);
}
exit();
