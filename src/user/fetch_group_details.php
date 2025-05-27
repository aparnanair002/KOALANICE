<?php
session_start();
include '../dboperation.php';

$con = new dboperation();

// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {  
        header('Location: ../guest/login.php');

    exit();
}

// Check for group_id
if (!isset($_GET['group_id']) || empty($_GET['group_id'])) {
    echo json_encode(['success' => false, 'message' => 'Group ID is required']);
    exit();
}

$id = $_GET['group_id'];

// UNPARAMETERIZED query (as per your current preference)
$query = "SELECT * FROM groups WHERE id = '$id'";
$stmt = $con->executequery($query);

if ($stmt && mysqli_num_rows($stmt) > 0) {
    $row = mysqli_fetch_assoc($stmt);
    $profilePic = !empty($row['groupic']) ? $row['groupic'] : '../assets/images/profile/kind.jpg';
    $name = htmlspecialchars($row['group_name'], ENT_QUOTES, 'UTF-8');

    $html = '
        <img src="' . htmlspecialchars($profilePic, ENT_QUOTES, 'UTF-8') . '" 
             alt="Profile" 
             class="rounded-circle" 
             width="50" 
             height="50" 
             style="object-fit: cover;">
        <div class="ms-3 flex-grow-1">
            <h4 class="mb-0" style="color:white;">' . $name . '</h4>
        </div>
        
                            <i class="ti ti-users" style="color:white; font-size: 1.2rem; cursor: pointer; padding:15px" id="user"></i>

            <i class="ti ti-flag" style="color:white; font-size: 1.2rem; cursor: pointer; padding:15px" id="report"></i>
            <i class="ti ti-trash" style="color:white; font-size: 1.2rem; cursor: pointer; padding:15px" id="deleteConvo"></i>
        <i class="ti ti-logout" style="color:white; font-size: 1.2rem; cursor: pointer; padding:15px" title="leave group" id="exit"></i>

    ';

    echo json_encode(['success' => true, 'html' => $html]);
} else {
    echo json_encode(['success' => false, 'message' => 'Group not found']);
}

$stmt->close();
$con->con->close();
?>
