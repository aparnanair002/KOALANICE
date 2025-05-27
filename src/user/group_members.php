<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

// JSON response array
$response = [
    'success' => false,
    'html' => '<p>Something went wrong.</p>'
];

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    $response['html'] = '<p>Unauthorized access.</p>';
        header('Location: ../guest/login.php');

    echo json_encode($response);
    exit();
}

if (isset($_POST['group_id'])) {
    $group_id = intval($_POST['group_id']);
    $sql = "SELECT * FROM group_members g 
            INNER JOIN users u ON u.id = g.user_id
            WHERE g.group_id = $group_id order by g.status asc";

    $resquery = $con->executequery($sql);

    if ($resquery && mysqli_num_rows($resquery) > 0) {
        $html = '';
        while ($row = mysqli_fetch_assoc($resquery)) {
            // Determine badge class based on status
            $status = $row['status'] ?? 'not_in_group';
            switch ($status) {
                case 'Requested':
                    $badgeClass = 'bg-warning';
                    break;
                case 'Added':
                    $badgeClass = 'bg-success';
                    break;
                case 'rejected':
                    $badgeClass = 'bg-danger';
                    break;
                default:
                    $badgeClass = 'bg-secondary'; // for "not in group" or unknown
                    $status = 'Not in group';     // override display text
            }

            $profilePic = !empty($row['profile_pic']) ? $row['profile_pic'] : '../assets/images/profile/kind.jpg';
            $html .= '
           <div class="card mb-2" >
                <div class="card-body d-flex justify-content-between align-items-center">
                    <img src="' . htmlspecialchars($profilePic) . '" 
                         alt="Profile" 
                         class="rounded-circle" 
                         width="70" 
                         height="70" 
                         style="object-fit: cover;">
                    <div class="ms-3 flex-grow-1">
                        <h4 class="mb-0">' . htmlspecialchars($row['name']) . '</h4>
                        <h4 class="mb-0 badge ' . $badgeClass . ' rounded-3 fw-semibold">' . htmlspecialchars($row['status']) . '</h4>
                        <i class="ti ti-trash" style="color: red; cursor: pointer;" onclick="deleteMember(' . $row['user_id'] . ',' . $group_id . ')" title="Delete"></i>
                    </div>
                </div>
            </div>';
        }
        $response['success'] = true;
        $response['html'] = $html;
    } else {
        $response['html'] = '<p>No members found in this group.</p>';
    }
} else {
    $response['html'] = '<p>Invalid request. Group ID is missing.</p>';
}

echo json_encode($response);
exit();
?>
