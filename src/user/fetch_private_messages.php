<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

if (!isset($_SESSION['userid']) || empty($_POST['receiver_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing user or receiver']);
        header('Location: ../guest/login.php');

    exit();
}

$sender_id = $_SESSION['userid'];
$receiver_id = intval($_POST['receiver_id']);

// UNPARAMETERIZED SQL query
$sql = "
    SELECT from_user_id, to_user_id, content, timestamp,message_status
    FROM private_chat_messages 
    WHERE 
        (from_user_id = '$sender_id' AND to_user_id = '$receiver_id') 
        OR 
        (from_user_id = '$receiver_id' AND to_user_id = '$sender_id') 
    ORDER BY timestamp ASC
";

$resquery = $con->executequery($sql);

$html = '<div class="chat-messages">';

while ($row = mysqli_fetch_assoc($resquery)) {
    $isSender = $row['from_user_id'] == $sender_id;
        $isread=$row['message_status']?'&nbsp;&nbsp;&nbsp;✔✔':'&nbsp;&nbsp;&nbsp;✔';

    $html .= '
    <div class="d-flex mb-2 ' . ($isSender ? 'justify-content-end' : 'justify-content-start') . '">
        <div class="p-2 rounded text-white ' . ($isSender ? 'bg-primary' : 'bg-success') . '" style="
            max-width: 70%;
            text-align: ' . ($isSender ? 'right' : 'left') . ';
        ">
            <div style="white-space: pre-wrap;">' . htmlspecialchars($row['content']) . '</div>
            <small class="text-light d-block mt-1" style="font-size: 0.75rem;">' . date('h:i A', strtotime($row['timestamp'])) .$isread. '</small>
            
        </div>
    </div>';
}

$html .= '</div>';

echo json_encode(['success' => true, 'html' => $html]);
?>
