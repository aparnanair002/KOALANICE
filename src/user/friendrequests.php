<?php
session_start();
header('Content-Type: application/json');
include '../dboperation.php';

$con = new dboperation();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
        header('Location: ../guest/login.php');

    echo json_encode(['count' => 0]);
    exit();
}

$userid = $con->con->real_escape_string($_SESSION['userid']);

// Query to get pending requests count for the logged-in user
$sql = "
    SELECT COUNT(*) as count 
    FROM tbl_requests re 
    WHERE re.reciever_id = '$userid' 
      AND re.status = 'pending'
";

$res = $con->executequery($sql);

if ($res && $row = mysqli_fetch_assoc($res)) {
    echo json_encode(['count' => (int)$row['count']]);
} else {
    echo json_encode(['count' => 0]);
}

// Clean up
if ($res) {
    mysqli_free_result($res);
}
$con->con->close();
exit();
?>
