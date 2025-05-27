<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
        header('Location: ../guest/login.php');

    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['userid'];
$query = "
  SELECT g.*, COUNT(m.user_id) AS member_count
  FROM groups g
  LEFT JOIN group_members m ON g.id = m.group_id
  WHERE g.groupadmin ='$user_id' and m.status = 'added'
  GROUP BY g.id
";
$result= $con->executequery($query);

$html = '<div class="d-flex overflow-auto gap-3" style="padding: 10px;">';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $profilePic = !empty($row['groupic']) ? $row['groupic'] : '../assets/images/profile/kind.jpg';
        $html .= '
        <div class="group-card card me-3 flex-shrink-0" style="min-width: 200px; max-width: 200px;" onclick="window.location.href=\'groupindividual.php?id=' . $row['id'] . '\'">
            <div class="d-flex justify-content-between align-items-center mt-3 px-2">
                <div class="text-center flex-grow-1">
                    <img src="' . htmlspecialchars($profilePic) . '" 
                         alt="Profile" 
                         class="rounded-circle" 
                         width="70" 
                         height="70" 
                         style="object-fit: cover;">
                </div>
            </div>

            <div class="card-body">
                <h6 class="card-text text-center" style="font-size:15px">' . htmlspecialchars($row['group_name']) . '</h6>
                <p class="card-text small text-center">' . htmlspecialchars($row['description']) . '</p>
                <p class="card-text text-center mb-0">Members: ' . $row['member_count'] . '</p>
            </div>
        </div>';
    }
} else {
    $html .= '<div class="text-muted">No groups yet. Create one!</div>';
}

$html .= '</div>';

echo json_encode(['success' => true, 'html' => $html]);
?>