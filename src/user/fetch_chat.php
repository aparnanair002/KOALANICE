<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'message' => 'Missing user']);
        header('Location: ../guest/login.php');

    exit();
}

$sender_id = intval($_SESSION['userid']); // still cast to integer for some basic safety

// Unparameterized SQL query
$sql = "
    SELECT 
    u.name,
    u.id,
    u.profile_pic,
    COALESCE(unread.count_unread, 0) AS unread_messages
FROM (
    SELECT 
        CASE 
            WHEN from_user_id = $sender_id THEN to_user_id 
            ELSE from_user_id 
        END AS other_user_id, 
        MAX(timestamp) AS last_msg_time 
    FROM private_chat_messages 
    WHERE from_user_id = $sender_id OR to_user_id = $sender_id 
    GROUP BY other_user_id 
    ORDER BY last_msg_time DESC 
    LIMIT 10
) recent_convos 
JOIN users u ON u.id = recent_convos.other_user_id
LEFT JOIN (
    SELECT 
        from_user_id, 
        to_user_id, 
        COUNT(*) AS count_unread
    FROM private_chat_messages
    WHERE message_status = 0 AND to_user_id = $sender_id
    GROUP BY from_user_id, to_user_id
) unread ON unread.from_user_id = u.id AND unread.to_user_id = $sender_id
ORDER BY recent_convos.last_msg_time DESC;

";

// Execute the query
$result = $con->executequery($sql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'No conversations found.']);
    exit();
}

$html = '';
while ($row = mysqli_fetch_assoc($result)) {
    $profilePic = !empty($row['profile_pic']) ? $row['profile_pic'] : '../assets/images/profile/user-1.jpg';

    $html .= '
        <li class="sidebar-item">
            <a class="sidebar-link" aria-expanded="false" href="./messagebox.php?id=' . htmlspecialchars($row['id']) . '">
                <div class="d-flex align-items-center">
                    <div class="sidebar-icon">
                        <img src="' . htmlspecialchars($profilePic) . '" 
                             alt="Profile" 
                             class="rounded-circle" 
                             width="50" 
                             height="50" 
                             style="object-fit: cover;">
                    </div>';
                    if($row['unread_messages'] > 0) {
                        $html .= '
                        <div class="sidebar-text ms-3">
                            <h6 class="badge bg-danger rounded-circle mt-2">' . htmlspecialchars($row['unread_messages']) . '</h6>
                        </div>';
                    }

  $html .= '               
                    <div class="sidebar-text ms-3">
                        <h6 class="mb-0">' . htmlspecialchars($row['name']) . '</h6>
                    </div>
                    
                </div>                
            </a>
        </li>';
}

echo json_encode(['success' => true, 'html' => $html]);
?>
