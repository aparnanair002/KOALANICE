<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../dboperation.php';
$con = new dboperation();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
        header('Location: ../guest/login.php');

    exit();
}

$user_id = $_SESSION['userid'];
// Escape user_id to prevent SQL injection

    $sql = "SELECT 
    g.*, 
    COUNT(CASE WHEN gm.status = 'Added' THEN gm.user_id END) AS member_count
FROM groups g
LEFT JOIN group_members gm ON g.id = gm.group_id
WHERE g.id NOT IN (
    SELECT group_id
    FROM group_members
    WHERE user_id = '" . $user_id . "'
)
GROUP BY g.id;
";

$resquery = $con->executequery($sql);
if (!$resquery) {
    echo json_encode(['success' => false, 'message' => 'Query failed']);
    exit();
}
if (mysqli_num_rows($resquery) == 0) {
    echo json_encode(['success' => false, 'message' => 'No new groups found']);
    exit();
}

$html = '<ul class="list-group list-group-flush">';
while ($row = mysqli_fetch_assoc($resquery)) {
    $profilePic = !empty($row['groupic']) ? $row['groupic'] : '../assets/images/profile/kind.jpg';

    $html .= '
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <div class="position-relative border rounded p-2 mb-3 d-flex align-items-center w-100">
            <img src="' . htmlspecialchars($profilePic) . '" 
                 alt="Profile" 
                 class="rounded-circle" 
                 width="70" 
                 height="70" 
                 style="object-fit: cover;">
            
            <div class="ms-3 flex-grow-1">

              <h5 class="mb-0">' . htmlspecialchars($row['group_name']) . '</h5>
                            <h6 class="mb-0 mt-2">' . htmlspecialchars($row['description']) . '</h6>
                            <h6 class="mb-0 mt-2 text-success"> Members:' . htmlspecialchars($row['member_count']) . '</h6>

            </div>

            <div class="ms-auto" style="margin-left:20px;">
            
             <a href="javascript:void(0);" 
         data-reciever-id="' . $row['id'] . '" 
         class="position-absolute top-0 start-30 me-2 mt-2 text-danger join-group"
         title="Join Group" 
         style="font-size: 1.5rem;">
        <i class="bi bi-plus-circle-fill"></i>
      </a>
        </div></div>
    </li>';
}
$html .= '</ul>';

echo json_encode(['success' => true, 'html' => $html]);
exit();
