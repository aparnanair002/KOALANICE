<?php
session_start();
include '../dboperation.php';
$con = new dboperation();
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    // Return empty or error JSON
    header('Location: ../guest/login.php');
    exit();
}
$id = $_GET['id'] ?? null;
$sql = "SELECT id,name FROM users WHERE id = '$id'";
$result = $con->executequery($sql);
if (mysqli_num_rows($result) == 0) {
    // If no user found, redirect to the login page
    header('Location: ../guest/login.php');
    exit();
}
$result = mysqli_fetch_assoc($result);
$complaint_id = $result['id'];
$complaint_name = $result['name'];

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

            <?php include('./header.php'); ?>
            <div class="container-fluid">
                <!--  Row 1 -->
                <div class="row">

                    <div class="col-lg-4 d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">

                                    <div class="mb-9 mb-sm-0">
                                        <h5 class="card-title fw-semibold">Report &nbsp; <h3 style="color: rebeccapurple;margin-top:20px;margin-bottom:20px"><?php echo $complaint_name ?></h3>
                                        </h5>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="mb-9">
                                                <label for="exampleInputtext1" class="form-label">Issue</label>
                                                <!--select issue from report_issue table-->
                                                <select class="form-select" id="exampleInputtext1" name="report_issue" required>
                                                    <option value="" disabled selected>Select Issue</option>
                                                    <?php
                                                    // Fetch issues from the database
                                                    $sql = "SELECT * FROM report_issue";
                                                    $result = $con->executequery($sql);
                                                    while ($issue = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='" . htmlspecialchars($issue['issue_id']) . "'>" . htmlspecialchars($issue['issue_name']) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="mb-9">
                                                <label for="reason" class="form-label">Reason</label>
                                                <textarea class="form-control" id="reason" name="report_reason" rows="3" required></textarea>
                                            </div>
                                            <input type="hidden" name="report_against" value="<?php echo $complaint_id; ?>">
                                            <input type="hidden" name="reported_by" value="<?php echo $_SESSION['userid']; ?>">

                                            <!--image for proof-->
                                            <div class="mb-9">
                                                <label for="proof" class="form-label">Proof (required)</label>
                                                <input type="file" class="form-control" id="proof" name="proof" accept="image/*" required>
                                            </div>

                                            <button type="submit" name="submitbutton" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded-2">Report</button>

                                        </form>
                                        <?php
                                        if (isset($_POST['submitbutton'])) {
                                            $report_issue = $_POST['report_issue'];
                                            $report_reason = $_POST['report_reason'];
                                            $report_against = $_POST['report_against'];
                                            $reported_by = $_POST['reported_by'];

                                            // Handle file upload
                                            if (isset($_FILES['proof']) && $_FILES['proof']['error'] == 0) {
                                                $file_name = $_FILES['proof']['name'];
                                                $file_tmp = $_FILES['proof']['tmp_name'];
                                                $file_type = $_FILES['proof']['type'];

                                                // Validate file type (optional)
                                                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                                                if (in_array($file_type, $allowed_types)) {
                                                    // Move the uploaded file to a directory
                                                    $upload_dir = '../uploads/';
                                                    move_uploaded_file($file_tmp, $upload_dir . $file_name);

                                                    // Insert report into the database
                                                    $sql = "INSERT INTO tbl_reports (report_issue, report_reason, report_against, reported_by, proof) VALUES ('$report_issue', '$report_reason', '$report_against', '$reported_by', '$file_name')";
                                                    if ($con->executequery($sql)) {
                                                        echo "<div class='alert alert-success'>Report submitted successfully!</div>";
                                                    } else {
                                                        echo "<div class='alert alert-danger'>Error submitting report.</div>";
                                                    }
                                                } else {
                                                    echo "<div class='alert alert-danger'>Invalid file type. Only images are allowed.</div>";
                                                }
                                            } else {
                                                echo "<div class='alert alert-danger'>Please upload a proof image.</div>";
                                            }
                                        }


                                        ?>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                     <div class="col-lg-8 d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">

                                    <div class="mb-9 mb-sm-0">
                                        <h5 class="card-title fw-semibold">My reports <h3 style="color: rebeccapurple;margin-top:20px;margin-bottom:20px"><?php echo $complaint_name ?></h3>
                                        </h5>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Issue</th>
                                                    <th scope="col">Against</th>
                                                    <th scope="col">Reason</th>
                                                    <th scope="col">Proof</th>
                                                    <th scope="col">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $user_id = $_SESSION['userid'];
                                                $sql = "SELECT r.report_id, r.report_reason,r.report_against, r.proof, r.status, i.issue_name, u.name FROM tbl_reports r 
                                                inner JOIN report_issue i ON r.report_issue = i.issue_id
                                                inner JOIN users u ON r.report_against= u.id
                                                 WHERE r.reported_by = '$user_id'";
                                                $result = $con->executequery($sql);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row['issue_name']) . "</td>";
                                                     echo "<td>" . htmlspecialchars($row['name']) . "</td>";

                                                    echo "<td>" . htmlspecialchars($row['report_reason']) . "</td>";
                                                    echo "<td><img src='../uploads/" . htmlspecialchars($row['proof']) . "' alt='Proof' style='width: 100px; height: auto;'></td>";
                                                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>


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