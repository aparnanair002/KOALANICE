<?php
session_start();
header('Content-Type: application/json');
include './dboperation.php';

$con = new dboperation();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    echo json_encode(['count' => 0]);
    exit();
}

$userid = $con->con->real_escape_string($_SESSION['userid']);

// Fetch pending group requests sent to the logged-in user (as admin/owner of a group)
$sql = "
    SELECT COUNT(*) as count 
    FROM group_members gr 
    JOIN groups g ON gr.group_id = g.id 
    WHERE gr.user_id = '$userid' AND gr.status = 'Requested'
";

$result = $con->executequery($sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['count' => (int)$row['count']]);
    mysqli_free_result($result);
} else {
    echo json_encode(['count' => 0]);
}

$con->con->close();
exit();
?>
