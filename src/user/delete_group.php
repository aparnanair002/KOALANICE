<?php
session_start();
include '../dboperation.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!isset($_SESSION['userid'])) {
    header('Location: ../guest/login.php');
    exit();
}
$con = new dboperation();

header('Content-Type: application/json');

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid group id.']);
    exit;
}

// delete group members first
$deleteMembers = "DELETE FROM group_members WHERE group_id = '$id'";
$res=$con->executequery($deleteMembers);
if($res==1){

// delete group
$delete = "DELETE FROM groups WHERE id = ?";
$resi->$con($delete, $id);
if($resi==1){

    echo json_encode(['success' => true, 'message' => 'Group deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Group did not delete.']);
}}
else{
    echo json_encode(['success' => false, 'message' => 'Group members did not delete.']);
}


?>