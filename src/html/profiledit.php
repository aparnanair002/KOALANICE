<?php
session_start();
include './dboperation.php';
$con = new dboperation();

// Redirect if user not logged in
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];

//update changes
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['updateDetails'])) {
$name=$_POST['name'];
$password=$_POST['password'];
//updatee user details
$sql='update users set name="'.$name.'", password="'.$password.'" where id="'.$_SESSION['userid'].'"';
$request=$con->executequery($sql);
//check if update was successful
if($request){
    echo "<script>window.location.href='index.php'</script>";
    }else{
        echo "<script>alert('update was unsuccessful');</script>";
    }
}


// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $uploadDir = '../uploads/profile_pics/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
    $fileName = $_FILES['profile_pic']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExt, $allowedExt)) {
        $newFileName = 'user_'.$user_id.'_'.time().'.'.$fileExt;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Save relative path to DB
            $dbPath = '../uploads/profile_pics/' . $newFileName;

            $updateStmt = $con->con->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
            $updateStmt->bind_param("si", $dbPath, $user_id);
            $updateStmt->execute();
            $updateStmt->close();

            // Save path in session for immediate preview
            $_SESSION['profile_pic'] = $dbPath;

            // Reload page to show updated image
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $uploadError = "Error moving the uploaded file.";
        }
    } else {
        $uploadError = "Invalid file type. Only JPG, JPEG, PNG, GIF allowed.";
    }
}

// Fetch user details including profile image
$userData = null;
$query = "SELECT name, password FROM users WHERE id = ?";
$res=$con->executequery($query, $user_id);
if (mysqli_num_rows($res) === 1) {
    $userData = $result->fetch_assoc();
} else {
    echo "<script>alert('User data not found.');</script>";
}
$res->close();


?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Koalanice - Profile Edit</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo.svg" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
          <?php include('./sidebar.php'); ?>

    <div class="body-wrapper">
      <?php include('./header.php'); ?>

      <div class="container-fluid">
        <div class="row">
          <!-- Profile Image Upload -->
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Upload Profile Image</h5>

                <div class="text-center mb-4">
                  <img id="previewImage" src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" width="300" height="300" class="rounded-circle" />
                </div>

                <?php if (!empty($uploadError)) : ?>
                  <div class="alert alert-danger"><?php echo htmlspecialchars($uploadError); ?></div>
                <?php endif; ?>

                <form id="upload-profile-image" method="post" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="profileImage" class="form-label">Choose Profile Image</label>
                    <input type="file" class="form-control" id="profileImage" name="profile_pic" accept="image/*" required />
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Upload</button>
                </form>
              </div>
            </div>
          </div>

          <!-- Edit User Details -->
          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Edit Details</h5>
                <div class="table-responsive">
                  <form method="post">
                    <div class="mb-3">
                      <?php if (!empty($userData)): ?>

                      <label for="nameInput" class="form-label">Name</label>
                      <input type="text" id="nameInput" name="name" class="form-control" value="<?php echo isset($userData['name']) ? $userData['name'] : ''; ?>"  />
                    </div>
                    <div class="mb-3">
                      
                      <label for="passwordInput" class="form-label">Password</label>
                      <input type="password" id="passwordInput" name="password" class="form-control" value="<?php echo isset($userData['password']) ? $userData['password'] : ''; ?>"  />
                    </div>
                    <?php else: ?>
  <div class="alert alert-danger">User data could not be loaded.</div>
<?php endif; ?>
                    <button type="submit" name="updateDetails" class="btn btn-success">Save Changes</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('profileImage').addEventListener('change', function (event) {
      const [file] = event.target.files;
      if (file) {
        document.getElementById('previewImage').src = URL.createObjectURL(file);
        alert('Click on the upload button to save your new profile image.');
      }
    });
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
