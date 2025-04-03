<?php
session_start();
require("config.php");

if(!isset($_SESSION['auser'])) {
    header("location:index.php");
}

$error="";
$msg="";

if(isset($_POST['add'])) {
    // Create documents directory if it doesn't exist
    $upload_dir = "documents/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Array to store uploaded file paths
    $file_paths = array();
    $upload_status = true;

    // Handle file uploads
    $files = array(
        'lease' => 'lease_doc',
        'contract' => 'contract_doc',
        'agreements' => 'agreements_doc',
        'ID' => 'id_doc'
    );

    foreach ($files as $input_name => $db_field) {
        if(isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
            $file = $_FILES[$input_name];
            $file_name = time() . '_' . $input_name . '_' . basename($file['name']);
            $target_path = $upload_dir . $file_name;
            
            $allowed = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if(!in_array($ext, $allowed)) {
                $error .= "Invalid file type for {$input_name}. Allowed types: pdf, doc, docx, jpg, jpeg, png<br>";
                $upload_status = false;
                continue;
            }

            if($file['size'] > 10485760) {
                $error .= "File size too large for {$input_name}. Maximum size: 10MB<br>";
                $upload_status = false;
                continue;
            }

            if(move_uploaded_file($file['tmp_name'], $target_path)) {
                $file_paths[$db_field] = $target_path;
            } else {
                $error .= "Failed to upload {$input_name}<br>";
                $upload_status = false;
            }
        } else {
            $error .= "Please select a file for {$input_name}<br>";
            $upload_status = false;
        }
    }

    // If all files uploaded successfully, store in database
    if($upload_status) {
        $id_type = $_POST['status'];
        $user_id = $_POST['user'];

        // Prepare SQL statement
        $sql = "INSERT INTO documents (lease_doc, contract_doc, agreements_doc, id_type, id_doc, user_id, upload_date) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($con, $sql);

        if($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssis",
                $file_paths['lease_doc'],
                $file_paths['contract_doc'],
                $file_paths['agreements_doc'],
                $id_type,
                $file_paths['id_doc'],
                $user_id
            );

            if(mysqli_stmt_execute($stmt)) {
                $msg = "Documents uploaded and stored successfully!";
            } else {
                $error = "Database error: " . mysqli_error($con);
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Database error: " . mysqli_error($con);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Philip And Aurea Residences</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="assets/css/feathericon.min.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>

		
			<!-- Header -->
			<?php include("header.php"); ?>
			<!-- /Sidebar -->
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col">
								<h3 class="page-title">Documents</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
									<li class="breadcrumb-item active">Document Management</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title">Document Management </h4>
								</div>
								<form method="post" enctype="multipart/form-data">
								<div class="card-body">
										<h4 class="card-title">Documents</h4>
                                        <p class="font-italic text-secondary small"> Note: Documents must be pdf, doc, docx, jpeg, or png</p>
										<div class="row">
											<div class="col-xl-6">

                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Tenant</label>
                                                    <select class="col-lg-9" required name="user">
                                                        <option value="">Select Tenant</option>
                                                        <?php
                                                        $query=mysqli_query($con,"select * from user where utype = 'user'");
                                                        while($row=mysqli_fetch_assoc($query)) {
                                                            ?>
                                                            <option value="<?php echo $row['uid'] ?>"><?php echo $row['uid'] . " " . $row['uname'] ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
												
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Lease</label>
													<div class="col-lg-9">
														<input class="form-control" name="lease" type="file" required="">
													</div>
												</div>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Contract</label>
													<div class="col-lg-9">
														<input class="form-control" name="contract" type="file" required="">
													</div>
												</div>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Tenant Agreements</label>
													<div class="col-lg-9">
														<input class="form-control" name="agreements" type="file" required="">
													</div>
												</div>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Type of ID</label>
													<div class="col-lg-9">
														<select class="form-control"  required name="status">
															<option value="">Select ID</option>
															<option value="national_id">National ID</option>
															<option value="philhealth">PhilHealth</option>
                                                            <option value="sss">SSS</option>
                                                            <option value="voters_id">Voter's ID</option>
                                                            <option value="umid">UMID</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Identification</label>
													<div class="col-lg-9">
														<input class="form-control" name="ID" type="file">
													</div>
												</div>
											</div>
										</div>

                                    <input type="submit" value="Submit" class="btn btn-primary" name="add" style="margin-left:200px;"><br>
                                    <?php
                                        echo $msg;
                                    ?>
								</div>
								</form>
							</div>
						</div>
					</div>
				
				</div>			
			</div>
			<!-- /Main Wrapper -->

		
		<!-- jQuery -->
        <script src="assets/js/jquery-3.2.1.min.js"></script>
		<script src="assets/plugins/tinymce/tinymce.min.js"></script>
		<script src="assets/plugins/tinymce/init-tinymce.min.js"></script>
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Slimscroll JS -->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		
		<!-- Custom JS -->
		<script  src="assets/js/script.js"></script>
		
    </body>

</html>