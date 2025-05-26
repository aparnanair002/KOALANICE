<?php
session_start();
include './dboperation.php';
$con = new dboperation();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$group_id = $_POST['group_id'] ?? null;
$search = $_POST['query'] ?? '';
$search = htmlspecialchars($search);

if (!$group_id) {
    echo '<p class="text-muted">Group ID is missing.</p>';
    exit();
}

// Escape inputs if executequery does not handle this internally
$group_id = $con->con->real_escape_string($group_id);
$search_escaped = $con->con->real_escape_string($search);

if (empty($search)) {
    $sql = "SELECT id, profile_pic, name FROM users WHERE id NOT IN (SELECT user_id FROM group_members WHERE group_id = '$group_id')";
} else {
    $sql = "SELECT id, profile_pic, name FROM users WHERE name LIKE '%$search_escaped%' AND id NOT IN (SELECT user_id FROM group_members WHERE group_id = '$group_id')";
}

$result = $con->executequery($sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="card mb-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                 <img src="' . htmlspecialchars($row['profile_pic']) . '" 
                      alt="Profile" 
                      class="rounded-circle" 
                      width="70" 
                      height="70" 
                      style="object-fit: cover;">
                    <div>
                        <h6 class="mb-0">' . htmlspecialchars($row['name']) . '</h6>
                    </div>    
                    <button class="btn btn-success btn-sm Request-user" data-id="' . $row['id'] . '">
                    <i class="ti ti-users"></i>
                    Add to Group</button>
               </div>
            </div>';
    }
} else {
    echo '<p class="text-muted">No users found.</p>';
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('click', '.Request-user', function() {
    var userId = $(this).data('id');
    var groupId = <?php echo json_encode($group_id); ?>;
    $.ajax({
        url: 'group_member_add.php',
        type: 'POST',
        dataType: 'json',
        data: { user_id: userId, group_id: groupId },
        success: function(response) {
            if (response.success) {
                alert('User added to group successfully!');
                window.location.reload();
            } else {
                alert('Error adding user to group: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while processing your request.');
        }
    });
});
</script>
