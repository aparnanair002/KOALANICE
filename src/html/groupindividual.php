<?php
session_start();
include './dboperation.php';
$con = new dboperation();

// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koalanice</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo.svg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<style>
    .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 20px;
        border-radius: 10px;
        z-index: 1000;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
    }

    .popup.show {
        display: block;
    }

    .group-card:hover {
        background: rgb(80, 98, 200);
        color: white;
    }

    #editGroupForm {
        background: #fff;
        padding: 20px;
    }

    #editGroupForm input[type="text"],
    #editGroupForm input[type="file"] {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    #editGroupForm button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
        data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <?php include('./sidebar.php'); ?>
        <div class="body-wrapper">
            <?php include('./header.php'); ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="d-sm-flex d-block align-items-center justify-content-between mb-4">
                                    <?php
                                    $group_id = intval($_GET['id']);
                                    $sql = "SELECT * FROM groups WHERE id = $group_id";
                                    $result = $con->con->query($sql);
                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $group_name = $row['group_name'];
                                        $description = $row['description'];
                                        $groupic = $row['groupic'] ?? "../assets/images/profile/kind.jpg";
                                        $groupadmin = $row['groupadmin'];
                                    } else {
                                        echo "<p>No group found.</p>";
                                        exit;
                                    }
                                    ?>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo $groupic; ?>" alt="group image" class="rounded-circle"
                                            width="150" height="150">
                                        <div class="ms-3">
                                            <h3 class="mb-0"><?php echo htmlspecialchars($group_name); ?></h3>
                                            <p class="mb-0 text-secondary"><?php echo htmlspecialchars($description); ?></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-pencil" id="edit-group-name" style="font-size: 30px; color: #4b4b4b; cursor: pointer;"></i>
                                        <i class="bi bi-trash" id="delete-group" style="font-size: 30px; color: #4b4b4b; cursor: pointer;"></i>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6 mb-3">
                                        <h5 class="card-title fw-semibold">Group Members</h5>

                                        <div id="memberList" style="max-height: 350px; overflow-x: auto;">
                                            <!-- Populate with group members -->
                                        </div>
                                    </div>
                                    <!-- Optional: Second column for different group content -->
                                    <div class="col-lg-6 mb-3">
                                        <h5 class="card-title fw-semibold">Request users</h5>

                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Search users..." id="searchInput">
                                        </div>
                                        <div id="userList" class="mt-3"
                                            style="max-height: 250px; overflow-y: auto;">
                                            <!-- Populate with different content if needed -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            document.getElementById('edit-group-name').addEventListener('click', function() {
                console.log('Edit group clicked');
                const groupName = document.querySelector('h3.mb-0').innerText;
                const groupDescription = document.querySelector('p.text-secondary').innerText;
                const currentGroupImage = document.querySelector('.d-flex.align-items-center img.rounded-circle').src;

                const popup = document.createElement('div');
                popup.className = 'popup show';
                popup.innerHTML = `
        <div class="modal-content">
            <span class="close" style="float:right;cursor:pointer;">&times;</span>
            <h3 class="text-center">Enter Details</h3>
            <form id="editGroupForm">
                <div class="text-center mb-4">
                    <img id="previewImage" src="${currentGroupImage}" alt="Profile Preview" width="150" height="150" class="rounded-circle" />
                </div>
                <input type="text" class="form-control" name="name" value="${groupName}" placeholder="Name" required>
                <textarea name="description" class="form-control" placeholder="Description" rows="3" required>${groupDescription}</textarea>
                <input type="file" class="form-control" name="profile_image" id="profileInput" accept="image/*">
                <button class="edit-group" type="submit">Submit</button>
            </form>
        </div>
    `;
                document.body.appendChild(popup);

                // Close handler
                popup.querySelector('.close').onclick = function() {
                    popup.remove();
                };

                // Preview image handler
                const fileInput = popup.querySelector('#profileInput');
                const previewImage = popup.querySelector('#previewImage');
                fileInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const imageURL = URL.createObjectURL(file);
                        previewImage.src = imageURL;
                    }
                });

                // Form submit handler
                popup.querySelector('#editGroupForm').onsubmit = function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    console.log('Form data:', formData);
                    formData.append('group_id', <?php echo $group_id; ?>);

                    $.ajax({
                        url: 'edit_group.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            try {
                                const res = JSON.parse(response);

                                document.querySelector('h3.mb-0').innerText = res.group_name;
                                document.querySelector('p.text-secondary').innerText = res.description;


                                if (res.groupic) {
                                    document.querySelector('.d-flex.align-items-center img.rounded-circle').src = res.groupic + '?t=' + new Date().getTime();
                                }

                                alert("Group updated successfully!");

                                popup.remove();
                            } catch (e) {
                                alert("Error: " + response);
                                popup.remove();
                                window.location.reload();
                            }
                        },
                        error: function() {
                            alert("Failed to send request.");
                        }
                    });
                };
            });

            // Delete group handler
            document.getElementById('delete-group').addEventListener('click', function() {
                if (confirm("Are you sure you want to delete this group?")) {
                    $.ajax({
                        url: 'delete_group.php?id=' + <?php echo $group_id; ?>,
                        type: 'GET',
                        success: function(response) {
                            try {
                                const res = typeof response === 'string' ? JSON.parse(response) : response;
                                if (res.success) {
                                    alert(res.message);
                                    window.location.href = 'index.php';
                                } else {
                                    alert('Delete failed: ' + res.message);
                                }
                            } catch (e) {
                                alert('Error parsing delete response.');
                            }
                        },
                        error: function() {
                            alert("Failed to delete group.");
                        }
                    });
                }
            });
            //view group members

            $.ajax({
                url: 'group_members.php',
                method: 'POST',
                data: {
                    group_id: <?php echo $group_id; ?>
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Insert the returned HTML inside a container div (e.g., #memberList)
                        $('#memberList').html(response.html);

                    } else {
                        $('#memberList').html('<p>No users found.</p>');

                    }
                },
                error: function() {
                    $('#memberList').html('<p>Failed to load users.</p>');
                }
            });

            $.ajax({
                url: 'search_users.php', // PHP file to query DB
                method: 'POST',
                data: {
                    group_id: <?php echo $group_id ?>
                },
                success: function(data) {
                    $('#userList').html(data); // Inject results
                }
            });

            $('#searchInput').on('keyup', function() {
                const query = $(this).val();
                if (query.length >= 1) {
                    $.ajax({
                        url: 'search_users.php', // PHP file to query DB
                        method: 'POST',
                        data: {
                            group_id: <?php echo $group_id ?>,
                            query: query
                        },
                        success: function(data) {
                            $('#userList').html(data); // Inject results
                        }
                    });
                } else {
                    $.ajax({
                        url: 'search_users.php', // PHP file to query DB
                        method: 'POST',
                        data: {
                            group_id: <?php echo $group_id ?>
                        },
                        success: function(data) {
                            $('#userList').html(data); // Inject results
                        }
                    });
                }
            });
        });

        function deleteMember(userId, groupId) {
            if (confirm("Are you sure you want to delete this member?")) {
                $.ajax({
                    url: 'delete_member.php',
                    type: 'POST',
                    data: {
                        user_id: userId,
                        group_id: groupId
                    },
                    success: function(response) {
                        try {
                            const res = typeof response === 'string' ? JSON.parse(response) : response;
                            if (res.success) {
                                alert(res.message);
                                // Optionally, you can remove the member from DOM or refresh the page
                                location.reload(); // simple page refresh
                            } else {
                                alert('Delete failed: ' + res.message);
                            }
                        } catch (e) {
                            alert('Error parsing delete response.');
                        }
                    },
                    error: function() {
                        alert("Failed to delete member.");
                    }
                });
            }
        }
    </script>



    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>

</html>