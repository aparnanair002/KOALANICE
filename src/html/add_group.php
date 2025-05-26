<?php
session_start();
include './dboperation.php';
$con = new dboperation();

// Check if logged in
if (!isset($_SESSION['userid'])) {
    http_response_code(403);
    echo 'Not authorized';
    exit;
}

$user_id = $_SESSION['userid'];

// Validate and sanitize input
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');

if (empty($name) || empty($description)) {
    echo 'All fields are required.';
    exit;
}

// Handle image upload
$targetDir = '../uploads/groups/';
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$imagePath = null;
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['profile_image']['tmp_name'];
    $fileName = uniqid() . '_' . basename($_FILES['profile_image']['name']);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($fileTmp, $targetFile)) {
        $imagePath = $targetFile;
    } else {
        echo 'Failed to upload image.';
        exit;
    }
}

// Insert into DB
$stmt = "INSERT INTO groups (group_name, description, groupic, groupadmin) VALUES ('$name', '$description', '$imagePath', '$user_id')";
$res=$con->executequery($stmt);
if ($res==1) {
    echo 'Group created successfully.';
    //get the group id
    $group_id = mysqli_insert_id($con->con);
    $status="Added";
    //imsert into groupmembers
    $stmti = "INSERT INTO group_members (group_id, user_id,status) VALUES ('$group_id', '$user_id', '$status')";
    $insertedresult=$con->executequery($stmti);
    if ($insertedresult==1) {
        echo 'User added to group successfully'.$group_id.' '.$user_id;
    } else {
        echo 'Error adding user to group: ' . mysqli_error($con->con);
    }
} else {
    echo 'Error creating group: ' . mysqli_error($con->con);
    exit;
}

$con->con->close();
