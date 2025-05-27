<?php
session_start();
include("../dboperation.php");
$con = new dboperation();
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modernize Free</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo.svg" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <!-- Left Column: Logo + Name + Desc -->
           
          <div class="col-md-6 text-center mb-5 mb-md-0">
            <h1 style="font-size: 36px; font-weight: bold; font-family: 'Lucida Sans', Geneva, Verdana, sans-serif; margin-top: 20px;">
              Koalanice
            </h1>
            <p style="font-size: 18px; color: #666;">Your Social Circle</p>
            <img src="../assets/images/logos/logo.svg" width="350" alt="Logo" style="border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            
          </div>

          <!-- Right Column: Form -->
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h4 class="text-center mb-4">Create Your Account</h4>
                <form action="" method="POST">
                  <div class="mb-3">
                    <label for="exampleInputtext1" class="form-label">Name</label>
                    <input type="text" class="form-control" id="exampleInputtext1" name="petname" required>
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="username" required>
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
                  </div>
                  <button type="submit" name="submitbutton" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded-2">Sign Up</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="mb-0 fw-bold">Already have an account?</p>
                    <a class="text-primary fw-bold ms-2" href="./login.php">Sign In</a>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    
  </div>
<?php
if (isset($_POST['submitbutton'])) {
    $username = $con->con->real_escape_string($_POST['username']);
    $password = $con->con->real_escape_string($_POST['password']);
    $petname = $con->con->real_escape_string($_POST['petname']);

    $check_sql = "SELECT * FROM users WHERE username = '$username'";
    $sql_present = $con->executequery($check_sql);

    if (mysqli_num_rows($sql_present) > 0) {
        echo "<script>alert('Username already exists'); window.location.href='register.php';</script>";
    } else {
        $sql = "INSERT INTO users (username, password, name) VALUES ('$username', '$password', '$petname')";
        $stmt = $con->executequery($sql);

        if ($stmt) {
            echo "<script>alert('Registered successfully'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . $con->con->error;
        }
    }
}
?>

</body>

</html>
