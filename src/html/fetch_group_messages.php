<?php
session_start();
include './dboperation.php';
$con = new dboperation();

if (!isset($_SESSION['userid']) || !isset($_POST['group_id']) || empty($_POST['group_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing user or group ID']);
    exit();
}

$group_id = intval($_POST['group_id']);
$user_id = $_SESSION['userid'];

// UNPARAMETERIZED query (for your current use case)
$sql = "
    SELECT m.*, u.name, u.profile_pic
    FROM messages m
    JOIN users u ON m.user_id = u.id
    WHERE m.group_id = '$group_id'
    ORDER BY m.sent_at ASC
";

$result = $con->executequery($sql);

$html = '';

while ($row = mysqli_fetch_assoc($result)) {
    $isSender = $row['user_id'] == $user_id;
    $username = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
    $message = nl2br(htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8')); // Escaping message content
    $profilePic = !empty($row['profile_pic']) ? $row['profile_pic'] : '../assets/images/profile/user-1.jpg';
    $timestamp = date('h:i A', strtotime($row['sent_at']));

    $html .= '
    <div class="d-flex mb-2 ' . ($isSender ? 'justify-content-end' : 'justify-content-start') . '">
        ' . (!$isSender ? '
        <img src="' . htmlspecialchars($profilePic) . '" 
             alt="Profile" 
             class="rounded-circle me-2" 
             width="40" 
             height="40" 
             style="object-fit: cover;">' : '') . '
        
        <div class="p-2 rounded text-white ' . ($isSender ? 'bg-primary' : 'bg-success') . '" style="max-width: 70%; text-align: ' . ($isSender ? 'right' : 'left') . ';">
            <div>' . $message . '</div>
            <small class="text-light d-block mt-1" style="font-size: 0.75rem;">' . $timestamp . '</small>
        </div>

        ' . ($isSender ? '
        <img src="' . htmlspecialchars($profilePic) . '" 
             alt="Profile" 
             class="rounded-circle ms-2" 
             width="40" 
             height="40" 
             style="object-fit: cover;">' : '') . '
    </div>
    ';
}

echo json_encode(['success' => true, 'html' => $html]);
?>
