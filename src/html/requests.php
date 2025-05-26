<?php
session_start();
include './dboperation.php';
$con = new dboperation();

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$userid = $_SESSION['userid'];
$action = $_GET['action'] ?? '';

if ($action === 'get_requests') {
    $data = getPendingFriendRequests($userid);
    echo json_encode(['success' => true, 'html' => $data['html']]);
    exit();
}

if ($action === 'get_invites') {
    $data = getPendingGroupInvites($userid);
    
    echo json_encode(['success' => true, 'html' => $data['html']]);
    exit();
}

if ($action === 'getgrouplist') {
    $data = getgrouplist($userid);
    
    echo json_encode(['success' => true, 'html' => $data['html']]);
    exit();
}

if ($action === 'get_friend_List') {
$data = getfriendslist($userid);
    
    echo json_encode(['success' => true, 'html' => $data['html']]);
    exit();
}


// ✅ Get pending friend requests
function getPendingFriendRequests($userid) {
    global $con;
    $stmt = "
        SELECT * FROM tbl_requests re 
        JOIN users u ON re.sender_id = u.id 
        WHERE re.reciever_id = '$userid' AND re.status = 'pending';
    ";
    $res= $con->executequery($stmt);

    $html = '<ul class="list-group list-group-flush">';
    $count = 0;
  if (mysqli_num_rows($res) == 0) {
        $html .= '<li class="list-group-item">No Requests found.</li>';
    }
    while ($row = mysqli_fetch_assoc($res)) {
        $count++;
        $profilePic = !empty($row['profile_pic']) ? $row['profile_pic'] : '../assets/images/profile/user-1.jpg';

        $html .= '
  <li class="list-group-item">
    <div class="d-flex align-items-center justify-content-between border rounded p-3" >
        <!-- Left Section: Profile -->
        <div class="d-flex align-items-center ">
            <img src="' . htmlspecialchars($profilePic) . '" 
                 alt="Profile" 
                 class="rounded-circle" 
                 width="70" 
                 height="70" 
                 style="object-fit: cover;">
            <div class="ms-3 p-3">
                <h4 class="mb-0">' . htmlspecialchars($row['name']) . '</h4>
            </div>
        </div>

        <!-- Right Section: Buttons -->
        <div class="d-flex align-items-right" style="margin-left: 70px;">
            <button class="btn btn-primary btn-sm me-2" onclick="acceptRequest(' . $row['req_id'] . ')">Accept</button>
            <button class="btn btn-danger btn-sm" onclick="rejectRequest(' . $row['req_id'] . ')">Reject</button>
        </div>
    </div>
</li>
';
    }

    $html .= '</ul>';

    $res->close();

    return [
        'success' => true,
        'count' => $count,
        'html' => $html
    ];
}



function getfriendslist($userid) {
    global $con, $userid;

    $stmt = "
        SELECT DISTINCT u.*, r.req_id 
        FROM tbl_requests r 
        JOIN users u ON (
            (r.sender_id = u.id AND r.reciever_id = '$userid') OR 
            (r.reciever_id = u.id AND r.sender_id = '$userid')
        )
        WHERE r.status = 'accepted' AND u.id != '$userid';
    ";
    $res=$con->executequery($stmt);

    $html = '<ul class="list-group list-group-flush">'; 
    $count = 0;
    if (mysqli_num_rows($res) == 0) {
        $html .= '<li class="list-group-item">No friends found.</li>';
    }
    while (    $row = mysqli_fetch_assoc($res)
) {
        $count++;
        $profilePic = !empty($row['profile_pic']) ? $row['profile_pic'] : '../assets/images/profile/user-1.jpg';

        $html .= '
        <li class="list-group-item">
            <div class="d-flex align-items-center justify-content-between border rounded p-3">
                <div class="d-flex align-items-center" style="max-width: 600px;">
                    <img src="' . htmlspecialchars($profilePic) . '" 
                         alt="Profile" 
                         class="rounded-circle" 
                         width="70" 
                         height="70" 
                         style="object-fit: cover;">
                    <div class="ms-3 p-3">
                        <h4 class="mb-0">' . htmlspecialchars($row['name']) . '</h4>
                    </div>
                </div>
                <div class="d-flex align-items-right" style="margin-left: 70px;">
                    <a href="./messagebox.php?id='.htmlspecialchars($row['id']).'"><i class="ti ti-message-circle fs-6" style="margin-right:30px;"></i></a>
                    <a href="./reportuser.php"><i class="ti ti-flag fs-6" style="margin-right:30px;"></i></a>
                    <button class="btn btn-primary btn-sm me-2" onclick="blockfriend(' . $row['req_id'] . ')">Block</button>
                </div>
            </div>
        </li>';
    }

    $html .= '</ul>';
    $res->close();

    return [
        'success' => true,
        'count' => $count,
        'html' => $html
    ];
}

// ✅ Get pending group invites
function getPendingGroupInvites($userid) {
    global $con;
    $stmt = "
        SELECT * FROM group_members re 
        JOIN groups u ON re.group_id = u.id 
        WHERE re.user_id = '$userid' AND re.status = 'Requested';
    ";
    $result = $con->executequery($stmt);  // ✅ FIXED: removed stray $

    $html = '<ul class="list-group list-group-flush">';
    $count = 0;

    if (mysqli_num_rows($result) == 0) {
        $html .= '<li class="list-group-item">No group invites found.</li>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $count++;
        $profilePic = !empty($row['groupic']) ? $row['groupic'] : '../assets/images/profile/kind.jpg';

        $html .= '
        <li class="list-group-item">
            <div class="d-flex align-items-center justify-content-between border rounded p-3">
                <div class="d-flex align-items-center">
                    <img src="' . htmlspecialchars($profilePic) . '" 
                         alt="Profile" 
                         class="rounded-circle" 
                         width="70" 
                         height="70" 
                         style="object-fit: cover;">
                    <div class="ms-3 p-3">
                        <h4 class="mb-0">' . htmlspecialchars($row['group_name']) . '</h4>
                        <h6 class="mb-0 mt-2">' . htmlspecialchars($row['description']) . '</h6>
                    </div>
                </div>
                <div class="d-flex align-items-right" style="margin-left: 70px;">
                    <button class="btn btn-primary btn-sm me-2" onclick="acceptInvite(' . $row['member_id'] . ')">Accept</button>
                    <button class="btn btn-danger btn-sm" onclick="rejectInvite(' . $row['member_id'] . ')">Reject</button>
                </div>
            </div>
        </li>';
    }

    $html .= '</ul>';
    $result->close();

    return [
        'success' => true,
        'count' => $count,
        'html' => $html
    ];
}



function getgrouplist($userid) {
    global $con;
    $sql = "
       SELECT * FROM group_members re 
        JOIN groups u ON re.group_id = u.id 
        WHERE re.user_id = '$userid' AND re.status = 'Added'; 
    ";
   $stmt=$con->executequery($sql);
    $html = '<ul class="list-group list-group-flush">'; 
    $count = 0;
    while ($row = mysqli_fetch_assoc($stmt)) {
        $count++;
        $profilePic = !empty($row['groupic']) ? $row['groupic'] : '../assets/images/profile/kind.jpg';

        $html .= '
    <li class="list-group-item">
        <div class="d-flex align-items-center justify-content-between border rounded p-3" >
            <!-- Left Section: Profile -->
            <div class="d-flex align-items-center" style="max-width: 600px;">
                <img src="' . htmlspecialchars($profilePic) . '" 
                     alt="Profile" 
                     class="rounded-circle" 
                     width="70" 
                     height="70" 
                     style="object-fit: cover;">
                <div class="ms-3 p-3">
                    <h4 class="mb-0">' . htmlspecialchars($row['group_name']) . '</h4>
                    <h6 class="mb-0 mt-2">' . htmlspecialchars($row['description']) . '</h6>

                </div>
            </div>
              <div class="d-flex align-items-right" style="margin-left: 70px;">
           <a href="./groupchats.php?id='.htmlspecialchars($row['id']).'"> <i class="ti ti-message-circle fs-6" style="margin-right:40px;"></i></a>
                      <a href="./reportuser.php"> <i class="ti ti-flag fs-6" style="margin-right:30px;"></i></a>

        </div>
        </div>
        </li>';
    }
    $html .= '</ul>';
        $stmt->close();

    return [
        'success' => true,
        'count' => $count,
        'html' => $html
    ];
    
}
?>
