<?php
session_start();
include './dboperation.php';
$con = new dboperation();


if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
  // Return empty or error JSON
  echo json_encode(['success' => false, 'message' => 'Not logged in']);
  exit();
}

$user_id = $_SESSION['userid'];

  // Return empty or error JSON

  $sql = "SELECT * FROM users 
    WHERE user_status = 'Accepted' 
    AND id != $user_id 
    AND id NOT IN (
        SELECT reciever_id FROM tbl_requests 
        WHERE sender_id = $user_id AND (status = 'Accepted' OR status = 'pending')
        UNION
        SELECT sender_id FROM tbl_requests 
        WHERE reciever_id = $user_id AND (status = 'Accepted' OR status = 'pending')
    )
    ORDER BY created_at DESC 
    LIMIT 5";

  $resquery = $con->executequery($sql);

  $html = '<ul class="list-group list-group-flush">';

  while ($row = mysqli_fetch_assoc($resquery)) {
    $profilePic = !empty($row['profile_pic']) ? $row['profile_pic'] : '../assets/images/profile/user-1.jpg';

    $html .= '
    <div class="position-relative border rounded p-2 mb-3 d-flex align-items-center w-100">
      <img src="' . htmlspecialchars($profilePic) . '" 
           alt="Profile" 
           class="rounded-circle" 
           width="70" 
           height="70" 
           style="object-fit: cover;">
      <a href="javascript:void(0);" 
         data-reciever-id="' . $row['id'] . '" 
         class="position-absolute top-0 start-30 me-2 mt-2 text-success send-request" 
         title="Send Request"
         style="font-size: 1.5rem;">
        <i class="bi bi-plus-circle-fill"></i>
      </a>
      <div class="ms-3 flex-grow-1">
        <h4 class="mb-0">' . htmlspecialchars($row['name']) . '</h4>
      </div>
    </div>';
  }

  $html .= '</ul>';

  echo json_encode(['success' => true, 'html' => $html]);

