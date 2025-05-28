<?php
session_start();
include_once '../dboperation.php';

$con = new dboperation();

if (isset($_POST['submitbutton'])) {
    // Escape user inputs to reduce injection risk (note: still not secure due to unparameterized query)
    $username = $con->con->real_escape_string($_POST['username']);
    $password = $con->con->real_escape_string($_POST['password']);

    // First check in users table
    $query_user = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND user_status = 'Accepted'";
    $stmt_user = $con->executequery($query_user);

    if ($stmt_user && mysqli_num_rows($stmt_user) > 0) {
        $user = mysqli_fetch_assoc($stmt_user);
        $_SESSION['userid'] = $user['id'];
        header("Location: ../user/index.php");
        exit();
    } else {
        // Now check in admin table
        $query_admin = "SELECT * FROM tbl_admin WHERE admin_name = '$username' AND admin_pass = '$password'";
        $stmt_admin = $con->executequery($query_admin);

        if ($stmt_admin && mysqli_num_rows($stmt_admin) > 0) {
            $admin = mysqli_fetch_assoc($stmt_admin);
            $_SESSION['adminid'] = $admin['admin_id'];
            header("Location: ../admin/index.php");
            exit();
        } else {
            echo "<script>alert('No such user or admin found');</script>";
        }
    }
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Koalanice</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo.svg" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body style="background-image:url('../assets/images/logos/Koalanice.svg');background-size:cover">
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../assets/images/logos/logo.svg" width="180" alt="" style="border-radius:50%">
                </a>
                <p class="text-center" style="text-align: center; font-size:30px;font-weight:bold;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif">Koalanice</p>

                <p class="text-center">Your Social Circle</p>
                <form action="" method="post">
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Username</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username" required>
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <!-- <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remember this Device
                      </label>
                    </div> -->
                    <!-- <a class="text-primary fw-bold" href="./index.html">Forgot Password ?</a> -->
                  </div>
                  <button type="submit" name="submitbutton" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded-2">Login</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">New to Koalanice?</p>
                    <a class="text-primary fw-bold ms-2" href="./register.php">Create an account</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
