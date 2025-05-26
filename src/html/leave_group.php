<?php
session_start();
include './dboperation.php';

$con = new dboperation();


// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Check for group_id
if (!isset($_POST['group_id']) || empty($_POST['group_id'])) {
    echo json_encode(['success' => false, 'message' => 'Group ID is required']);
    exit();
}

$id = $_POST['group_id'];

//delete from group_members table where user_id = $_SESSION['userid'] and group_id = $id
$query = "DELETE FROM group_members WHERE user_id = '" . $_SESSION['userid'] . "' AND group_id = '$id'";    
$stmt = $con->executequery($query);
echo json_encode(['success' => true, 'message' => 'You have left the group.']);
exit(); // Stop execution here

// clean up
if ($stmt && is_object($stmt)) {
    $stmt->close();
}
if ($con && $con->con) {
    $con->con->close();
}

?>
