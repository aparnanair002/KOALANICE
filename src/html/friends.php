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
            top: 30%;
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
    </style>

    <body>
        <!--  Body Wrapper -->
        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
            data-sidebar-position="fixed" data-header-position="fixed">
            <?php
            include('./sidebar.php');
            ?>
            <div class="body-wrapper">
                <!--  Header Start -->
                <?php
                include('./header.php');
                ?>
                <!--  Header End -->
                <div class="container-fluid">
                    <!--  Row 1 -->
                    <div class="row">
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">

                                        <div class="mb-3 mb-sm-0">
                                            <h5 class="card-title fw-semibold">New Requests <i class="ti ti-user"></i></h5>
                                            <div id="requestedList">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">

                                        <div class="mb-3 mb-sm-0">
                                            <h5 class="card-title fw-semibold">My Friends <i class="ti ti-heart"></i>
                                            </h5>

                                            <div id="friendList">
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
    </body>
    <script>
        $(document).ready(function () {
            loadFriends();
            loadRequested();
        });

        function loadRequested() {
            $.ajax({
                        url: 'requests.php',
                        method: 'GET',
                        dataType: 'json',
                        data: { action: 'get_requests' },
                        success: function(response) {
                            if (response.success) {
                            // Insert the returned HTML inside a container div (e.g., #usersList)
                            $('#requestedList').html(response.html);

                            } else {
                            $('#requestedList').html('<p>No users found.</p>');

                            }
                        },
                        error: function() {
                            $('#requestedList').html('<p>Failed to load users.</p>');
                        }
                        });
        }

        function loadFriends() {
            $.ajax({
                        url: 'requests.php',
                        method: 'GET',
                        dataType: 'json',
                        data: { action: 'get_friend_List' },
                        success: function(response) {
                            if (response.success) {
                            // Insert the returned HTML inside a container div (e.g., #usersList)
                            $('#friendList').html(response.html);

                            } else {
                            $('#friendList').html('<p>No users found.</p>');

                            }
                        },
                        error: function() {
                            $('#friendList').html('<p>Failed to load users.</p>');
                        }
                        });
        }
function acceptRequest(id) {
    $.ajax({
        url: 'acceptreject.php',
        method: 'POST',
        dataType: 'json',
        data: { action: 'accept_request', id: id, type: 'friendrequest' },
        success: function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert('Failed to accept request: ' + response.message);
            }
            location.reload();
        },
        error: function(xhr) {
            alert('Server error: ' + xhr.status);
        }
    });
}

function rejectRequest(id) {
    $.ajax({
        url: 'acceptreject.php',
        method: 'POST',
        dataType: 'json',
        data: { action: 'reject_request', id: id ,type:'friendrequest' },
        success: function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert('Failed to reject request: ' + response.message);
            }
            location.reload();
        },
        error: function(xhr) {
            alert('Server error: ' + xhr.status);
        }
    });
}

function block(id) {
    $.ajax({
        url: 'acceptreject.php',
        method: 'POST',
        dataType: 'json',
        data: { action: 'block', id: id ,type:'friendrequest' },
        success: function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert('Failed to reject request: ' + response.message);
            }
            location.reload();
        },
        error: function(xhr) {
            alert('Server error: ' + xhr.status);
        }
    });
}
        </script>  
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>

    </html>