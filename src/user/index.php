<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header('Location: ../guest/login.php');
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">

                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">New Users</h5>
                    <div id="usersList" class="col-lg-12">
                    </div>
                  </div>

                </div>
                <!--New users-->

              </div>
            </div>
          </div>

          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">

                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">New Groups</h5>
                    <div id="newgroupList" class="col-lg-12">
                    </div>
                  </div>

                </div>
                <!--New users-->

              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="row">
              <div class="col-lg-12">
                <!-- Yearly Breakup -->
                <div class="card overflow-hidden">
                  <div class="card-body p-4">
                    <h5 class="card-title mb-3 fw-semibold">Pending Requests</h5>

                    <div id="pendinglist" class="mt-3"
                      style="max-height: 230px; overflow-y: auto;">
                      <!-- Dynamic content will be loaded here -->
                    </div>

                  </div>
                </div>
              </div>

              <div class="col-lg-12">
                <!-- Monthly Earnings -->
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title mb-3 fw-semibold">Manage Groups</h5>

                    <div class="group-scroll-wrapper d-flex flex-row overflow-auto">
                      <!-- Single Group Card -->
                      <div id="groupList">

                      </div>



                      <!-- Add more group cards dynamically -->
                    </div>
                  </div>
                </div>

                <!--New users-->
                <script>
                  $(document).ready(function() {
                    // Fetch users list on page load
                    $.ajax({
                      url: 'fetch_users.php',
                      method: 'POST',
                      dataType: 'json',
                      success: function(response) {
                        if (response.success) {
                          // Insert the returned HTML inside a container div (e.g., #usersList)
                          $('#usersList').html(response.html);

                        } else {
                          $('#usersList').html('<p>No users found.</p>');

                        }
                      },
                      error: function() {
                        $('#usersList').html('<p>Failed to load users.</p>');
                      }
                    });
                    //fetch notification on load
                    // fetch group List on load
                    $.ajax({
  url: 'fetch_newgroups.php',
  method: 'POST',
  dataType: 'json',
  success: function(response) {
    if (response.success) {
      $('#newgroupList').html(response.html);
    } else {
      $('#newgroupList').html('<p>' + response.message + '</p>');
    }
  },
  error: function(jqXHR, textStatus, errorThrown) {
    console.error("AJAX Error:", textStatus, errorThrown);
    $('#newgroupList').html('<p>Failed to load groups.</p>');
  }
});

                    $.ajax({
                      url: 'pendingusers.php',
                      method: 'GET',
                      dataType: 'json',
                      success: function(response) {
                        if (response.success) {
                          // Insert the returned HTML inside a container div (e.g., #usersList)
                          $('#pendinglist').html(response.html);

                        } else {
                          $('#pendinglist').html('<p>No users found.</p>');

                        }
                      },
                      error: function() {
                        $('#pendinglist').html('<p>Failed to load users.</p>');
                      }
                    });

                    //fetch group deatils on load
                    $.ajax({
                      url: 'fetch_mygroups.php',
                      method: 'GET',
                      dataType: 'json',
                      success: function(response) {
                        if (response.success) {
                          // Insert the returned HTML inside a container div (e.g., #usersList)
                          $('#groupList').html(response.html);

                        } else {
                          $('#groupList').html('<p>No users found.</p>');

                        }
                      },
                      error: function(xhr, status, error) {
                        console.error("AJAX Error:", {
                          status: status,
                          error: error,
                          responseText: xhr.responseText
                        });

                        $('#groupList').html('<p>Failed to load users.</p><pre>' + xhr.responseText + '</pre>');
                        alert('AJAX Error:\n' + error + '\n' + xhr.responseText);
                      }
                    });

                    $(document).on('click', '.send-request', function() {
                      var recieverId = $(this).data('reciever-id');
                      var $icon = $(this);

                      $.ajax({
                        url: "send_request.php",
                        type: "GET",
                        data: {
                          reciever_id: recieverId
                        },
                        dataType: "json",
                        success: function(response) {
                          if (response.success) {
                            alert("Request sent successfully!");
                            $icon.html('<i class="bi bi-check-circle-fill text-muted"></i>');
                            $icon.removeClass('text-success send-request').addClass('text-muted');
                          } else {
                            alert(response.message || "Failed to send request.");
                          }
                        },
                        error: function() {
                          alert("An error occurred while sending the request.");
                        }
                      });


                    });



                     $(document).on('click', '.join-group', function() {
                      var grpId = $(this).data('reciever-id');
                      var $icon = $(this);

                      $.ajax({
                        url: "join-group.php",
                        type: "GET",
                        data: {
                          grpid: grpId
                        },
                        dataType: "json",
                        success: function(response) {
                          if (response.success) {
                            alert("Request sent successfully!");
                            $icon.html('<i class="bi bi-check-circle-fill text-muted"></i>');
                            $icon.removeClass('text-success join-group').addClass('text-muted');
                          } else {
                            alert(response.message || "Failed to send request.");
                          }
                        },
                        error: function() {
                          alert("An error occurred while sending the request.");
                        }
                      });


                    });

                  });

                    function deleterequest(userId) {
    if (!confirm('Are you sure you want to delete this request?')) return;

    $.ajax({
      url: 'delete_request.php',
      method: 'POST',
      dataType: 'json',
      data: { id: userId },
      success: function(response) {
        if (response.success) {
          alert('Request deleted successfully!');
          location.reload(); // Or remove the element from DOM
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function() {
        alert('AJAX error occurred.');
      }
    });
  }
                </script>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
  </div>
  <div id="successPopup" class="popup"></div>

  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/sidebarmenu.js"></script>
  <script src="../assets/js/app.min.js"></script>
  <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../assets/js/dashboard.js"></script>

</body>

</html>