<?php
session_start();
include '../dboperation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $con = new dboperation();

    $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
    $group_name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if ($group_id <= 0 || empty($group_name) || empty($description)) {
        echo "Invalid input data.";
        exit;
    }

    // Initialize groupic path variable
    $groupic_path = null;

    // Fetch current image path from DB
    $query = "SELECT groupic FROM groups WHERE id = $group_id";
    $result = $con->executequery($query);
    if (!$result || mysqli_num_rows($result) === 0) {
        echo "Error fetching group details.";
        exit;
    }

    $row = mysqli_fetch_assoc($result);
    $old_groupic = $row['groupic'];
    $result->free();

    // Handle file upload if exists
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $fileName = $_FILES['profile_image']['name'];
        $fileSize = $_FILES['profile_image']['size'];
        $fileType = $_FILES['profile_image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = '../uploads/groups/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $groupic_path = $dest_path;

                // Delete old image if exists
                if (!empty($old_groupic) && file_exists($old_groupic)) {
                    unlink($old_groupic);
                }
            } else {
                echo "Error moving the uploaded file.";
                exit;
            }
        } else {
            echo "Upload failed. Allowed file types: " . implode(',', $allowedfileExtensions);
            exit;
        }
    }

    // Build SQL update query
    if ($groupic_path !== null) {
        $sql = "UPDATE groups SET group_name = '$group_name', description = '$description', groupic = '$groupic_path' WHERE id = $group_id";
    } else {
        $sql = "UPDATE groups SET group_name = '$group_name', description = '$description' WHERE id = $group_id";
    }

    if ($con->executequery($sql) === TRUE) {
        echo "Group updated successfully.";
    } else {
        echo "Error updating group: " . $con->con->error;
    }

    $con->con->close();
} else {
    echo "Invalid request method.";
}
?>
