<?php
session_start();
include '../dboperation.php';
$con = new dboperation();
if (!isset($_SESSION['adminid']) || empty($_SESSION['adminid'])) {
  // Return empty or error JSON
  header('Location: ../guest/login.php');
  exit();
}

?>

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

          <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">

                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">Users</h5>

                    <?php

                    $sql = "SELECT * FROM users order by name ASC";


                    $resquery = $con->executequery($sql);

                    if (!$resquery) {
                      echo "Error in query execution: " . mysqli_error($con->con);
                    } else {
                      $num_rows = mysqli_num_rows($resquery);
                      if ($num_rows == 0) {
                        echo "<p>No users found.</p>";
                      } else {
                        $html = '<div style="max-height: 560px; overflow-y: auto;"><table class="table table-borderless">';
                        while ($row = mysqli_fetch_assoc($resquery)) {
                          $profilePic = !empty($row['profile_pic']) ? $row['profile_pic'] : '../assets/images/profile/user-1.jpg';

                          $html .= '
        <tr class="border rounded p-1 mb-2 align-items-center w-100 d-flex" style="font-size: 0.85rem;">
          <td style="width: 250px; padding: 0.25rem;">
            <img src="' . htmlspecialchars($profilePic) . '" 
                 alt="Profile" 
                 class="rounded-circle" 
                 width="50" 
                 height="50" 
                 style="object-fit: cover;">
          </td>
          <td class="ms-2 flex-grow-1 align-self-center" style="width: 250px; padding: 0.25rem;">
            <h6 class="mb-0">' . htmlspecialchars($row['name']) . '</h6>
          </td>
          <td class="ms-2 flex-grow-1 align-self-center" style="width: 250px; padding: 0.25rem;">
            <h6 class="badge bg-primary rounded">' . htmlspecialchars($row['user_status']) . '</h6>
          </td>';

                          if ($row['user_status'] == 'Accepted') {
                            $html .= '
          <td class="ms-2 flex-grow-1 align-self-center" style="width: 250px; padding: 0.25rem;">
            <button class="btn btn-danger btn-sm" onclick="changestatus(\'Reject\', ' . $row['id'] . ')" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Reject</button>
          </td>';
                          } else {
                            $html .= '<td class="ms-2 flex-grow-1 align-self-center" style="width: 250px; padding: 0.25rem;">
            <button class="btn btn-success btn-sm"  onclick="changestatus(\'Accepted\', ' . $row['id'] . ')" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Accept</button>
          </td>';
                          }
                          $html .= '
        </tr>';
                        }
                        $html .= '</table></div>';
                        echo $html;
                      }
                    }
                    ?>
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
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/sidebarmenu.js"></script>
  <script src="../assets/js/app.min.js"></script>
  <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../assets/js/dashboard.js"></script>

</body>

</html>