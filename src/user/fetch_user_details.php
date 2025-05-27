<?php
session_start();
include '../dboperation.php';

$con = new dboperation();

// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('Location: ../guest/login.php');
    
    exit();
}

if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit();
}

$id = $_GET['user_id'];

// UNPARAMETERIZED query (unsafe for production)
$query = "SELECT * FROM users WHERE id = '$id'";
$result = $con->executequery($query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $profilePic = !empty($row['profile_pic']) ? $row['profile_pic'] : '../assets/images/profile/user-1.jpg';
    $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');

    $html = '<img src="' . htmlspecialchars($profilePic) . '" 
                   alt="Profile" 
                   class="rounded-circle" 
                   width="50" 
                   height="50" 
                   style="object-fit: cover;">
             <div class="ms-3 flex-grow-1">
                 <h4 class="mb-0" style="color:white;">' . $name . '</h4>
             </div>
             <div class="d-flex justify-content-end">
                <a href="./reportuser.php?id='.$id.'"><i class="ti ti-flag" style="color:white; font-size: 1.2rem; cursor: pointer;padding:15px"></i></a>
                 <i class="ti ti-trash" style="color:white; font-size: 1.2rem; cursor: pointer;padding:15px" id="deleteConvo"></i>
             </div>';

    echo json_encode(['success' => true, 'html' => $html]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

// Clean up
mysqli_free_result($result);
$con->con->close();
?>
