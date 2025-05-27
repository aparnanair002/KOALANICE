<?php
session_start();
include '../dboperation.php';
$con = new dboperation();
// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('Location: ../guest/login.php');
    exit();
}
// Get user data from session
$sender_id = $_SESSION['userid'];

$sql = "select r.req_id,s.name,s.profile_pic,r.status from tbl_requests r inner join users s where r.sender_id = '$sender_id' and r.status = 'pending' and r.reciever_id=s.id";

$resquery = $con->executequery($sql);
if(mysqli_num_rows($resquery)>0)
{
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
      <div class="ms-3 flex-grow-1">
        <h4 class="mb-0">' . htmlspecialchars($row['name']) . '</h4>

  <h4 class="mb-0 ' .($row['status'] === 'pending' ? 'badge bg-warning rounded-3 fw-semibold' : 'badge bg-danger rounded-3 fw-semibold') . 
' " style="margin-top:10px;margin-right:60px;">'. htmlspecialchars($row['status']) . '</h4>
       <a  class="badge bg-danger rounded-3 fw-semibold" onclick="deleterequest('.htmlspecialchars($row['req_id']).')"> <i class="ti ti-trash"></i></a>

      </div>
    </div>';
}


$html .= '</ul>';

echo json_encode(['success' => true, 'html' => $html]);
}
else
{
    $html='No Pending Users ';
    echo json_encode(['success' =>true, 'html' =>$html]);
}
?>
