<?php
session_start();
include '../dboperation.php';
$con = new dboperation();
if (!isset($_SESSION['adminid']) || empty($_SESSION['adminid'])) {
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

          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">

                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">Add Issue</h5>
                    <form method="post" action="">
                      <div class="mb-3">
                        <label for="issueName" class="form-label">Issue Name</label>
                        <input type="text" class="form-control"  name="issueName" required>
                      </div>
                      <button type="submit" class="btn btn-primary" name="Submit">Add Issue</button>
                    </form>
                    <?php
                    if (isset($_POST['Submit'])) {
                        $issueName = $_POST['issueName'];
                        $sql="Insert into report_issue (issue_name) values ('$issueName')";
                        $resquery = $con->executequery($sql);

                        if (!$resquery) {
                            echo "<p class='text-danger'>Error adding issue: " . mysqli_error($con->con) . "</p>";
                        } else {

                            echo "<p class='text-success mt-3'>Issue added successfully.</p>";
                        }
                    }

                    ?>
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
                    <h5 class="card-title fw-semibold">Issues</h5>
                    <?php
                    $sqli = "SELECT * FROM report_issue ORDER BY issue_name ASC";
                    $resquer = $con->executequery($sqli);
                    if (mysqli_num_rows($resquer) == 0) {
                      echo "<p>No issues Registered.</p>";
                    } else {

                    
                    while ($row = mysqli_fetch_assoc($resquer)) {
                      echo "<div class='issue-item'>";
                      echo "<p><button class='btn btn-danger btn-sm' style='margin-left:20px;margin-right:20px;' onclick='deleteIssue(" . $row['issue_id'] . ")'>
                    <i class='bi bi-trash'></i> 
                    </button>" . htmlspecialchars($row['issue_name']) ."</p>";

                      echo "</div>";
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
  <script>
    function deleteIssue(issueId) {
      if (confirm("Are you sure you want to delete this issue?")) {
        $.ajax({
          url: 'delete_issue.php',
          type: 'POST',
          data: { id: issueId },
         success: function(response) {
  try {
    let json = JSON.parse(response);
    if (json.success) {
      alert(json.message);
      window.location.href = "./addreportissues.php";
    } else {
      alert(json.message);
    }
  } catch (e) {
    alert("Unexpected response from server.");
  }
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
