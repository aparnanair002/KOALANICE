<?php
session_start();
include '../dboperation.php';
$con = new dboperation();

// Redirect if user not logged in
if (!isset($_SESSION['adminid']) || empty($_SESSION['adminid'])) {
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

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <?php
    include('./sidebar.php');
    ?>
    <div class="body-wrapper">
      
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