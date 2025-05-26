<?php
session_start();
include './dboperation.php';
$con = new dboperation();

if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'message' => 'Missing user']);
    exit();
}

$sender_id = intval($_SESSION['userid']); // still cast to integer for some basic safety

// Unparameterized SQL query
$sql = "
SELECT DISTINCT g.group_name,g.groupic,g.id from messages m INNER JOIN groups g on g.id=m.group_id and m.user_id='$sender_id'  order by 
m.sent_at desc limit 5;
";

// Execute the query
$result = $con->executequery($sql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'No conversations found.']);
    exit();
}

$html = '';
while ($row = mysqli_fetch_assoc($result)) {
    $profilePic = !empty($row['groupic']) ? $row['groupic'] : '../assets/images/profile/user-1.jpg';

    $html .= '
        <li class="sidebar-item">
            <a class="sidebar-link" aria-expanded="false" href="./groupchats.php?id=' . htmlspecialchars($row['id']) . '">
                <div class="d-flex align-items-center">
                    <div class="sidebar-icon">
                        <img src="' . htmlspecialchars($profilePic) . '" 
                             alt="Profile" 
                             class="rounded-circle" 
                             width="50" 
                             height="50" 
                             style="object-fit: cover;">
                    </div>
                    <div class="sidebar-text ms-3">
                        <h6 class="mb-0">' . htmlspecialchars($row['group_name']) . '</h6>
                    </div>
                </div>                
            </a>
        </li>';
}

echo json_encode(['success' => true, 'html' => $html]);
?>
