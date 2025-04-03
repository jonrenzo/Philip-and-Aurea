<?php
session_start();
require("config.php");
////code
 
if(!isset($_SESSION['auser']))
{
	header("location:index.php");
}

if (isset($_POST['add'])) {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$contact = $_POST['contact'];

	$img_name = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];

	if ($error === 0) {

		$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
		$img_ex_lc = strtolower($img_ex);

		$allowed_exs = array("jpg", "jpeg", "png", "HEIC"); 

		if (in_array($img_ex_lc, $allowed_exs)) {

			$new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
			$img_upload_path = 'user/'.$new_img_name;
			move_uploaded_file($tmp_name, $img_upload_path);

			$sql="INSERT INTO user (uname,uemail,uphone,utype,uimage) VALUES('$name','$email','$contact','agent', '$new_img_name')";
			$result=mysqli_query($con,$sql);

			if($result){
				$msg = "<p class='alert alert-success'>Agent Added Successfully</p> ";
			}
			else{
				$msg = "<p class='alert alert-warning'>Register Not Successfully</p> ";
			}

			header("Location:useragent.php?msg=$msg");
		}

	}

}

if (isset($_POST['update'])) {
	$id = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$contact = $_POST['contact'];

	$img_name = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];

	if ($img_name == '') {
		$sql="UPDATE user SET uname = '$name', uemail = '$email', uphone = '$contact' WHERE uid = $id";
		$result=mysqli_query($con,$sql);

		if($result){
			$msg = "<p class='alert alert-success'>Agent Updated Successfully</p> ";
		}
		else{
			$msg = "<p class='alert alert-warning'>Register Not Successfully</p> ";
		}

		header("Location:useragent.php?msg=$msg");
	}
	else {
		if ($error === 0) {

			$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png", "HEIC"); 

			if (in_array($img_ex_lc, $allowed_exs)) {

				$new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                $img_upload_path = 'user/'.$new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);

				$sql="UPDATE user SET uname = '$name', uemail = '$email', uphone = '$contact', uimage = '$new_img_name' WHERE uid = '$id'";
				$result=mysqli_query($con,$sql);

				if($result){
					$msg = "<p class='alert alert-success'>Agent Updated Successfully</p> ";
				}
				else{
					$msg = "<p class='alert alert-warning'>Register Not Successfully</p> ";
				}

				header("Location:useragent.php?msg=$msg");
			}

		}
	}

	

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Philip and Aurea Residences</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="assets/css/feathericon.min.css">
		
		<!-- Datatables CSS -->
		<link rel="stylesheet" href="assets/plugins/datatables/dataTables.bootstrap4.min.css">
		<link rel="stylesheet" href="assets/plugins/datatables/responsive.bootstrap4.min.css">
		<link rel="stylesheet" href="assets/plugins/datatables/select.bootstrap4.min.css">
		<link rel="stylesheet" href="assets/plugins/datatables/buttons.bootstrap4.min.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>
	
		<!-- Main Wrapper -->
		
		
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
								<h3 class="page-title">Agent</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
									<li class="breadcrumb-item active">Agent</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-header" style="display: flex; justify-content: space-between">
									<div>
										<h4 class="card-title">Agent List</h4>
									</div>
									
									<button id="add" onclick="add()" class="btn btn-primary">+ Add Agent</button>
								</div>

								<?php 
									if(isset($_GET['msg']))	
										echo $_GET['msg'];	
								?>

								<div class="card-body">

									<table id="basic-datatable" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Contact</th>
                                                    <th>Image</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        
                                        
                                            <tbody>
											<?php
													
												$query=mysqli_query($con,"select * from user where utype='agent'");
												$cnt=1;
												while($row=mysqli_fetch_assoc($query))
													{
											?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row['uname']; ?></td>
                                                    <td><?php echo $row['uemail']; ?></td>
                                                    <td><?php echo $row['uphone']; ?></td>
                                                    <td><img src="user/<?php echo $row['uimage']; ?>" height="50px" width="50px"></td>
                                                    <td>
														<a <?php echo "onclick='edit($row[uid], \"$row[uname]\", \"$row[uemail]\", \"$row[uphone]\", \"$row[uimage]\")'" ?> class="btn  btn-primary text-white">Edit</a>
														<a href="useragentdelete.php?id=<?php echo $row['uid']; ?>"><button class="btn btn-danger">Delete</button></a></td>
                                                </tr>
                                                <?php
												$cnt=$cnt+1;
												} 
												?>
                                            
                                            </tbody>
                                        </table>
								</div>
							</div>
						</div>
					</div>
				
				</div>			
			</div>
			<!-- /Main Wrapper -->

			<section id="addSection">
				<div class="container-add">
					<h1>New Agent</h1>
					<hr>

					<form action="" method="post" enctype="multipart/form-data" id="addForm">

					<div class="img-containter">
						<img src="" alt="" id="showImage" width="200px">
					</div>
					<br>
					<input type="file" id="file" name="image" onchange="previewImage(event)">
					<label for="file" id="fileText" class="bg-primary">Upload Image</label><br>

						<div style="display: flex; justify-content: space-between; gap: 1rem">
							<div>
								<label>Name:</label><br>
								<input type="text" name="name" required><br>						
							</div>
							
							<div>
								<label>Email:</label><br>
								<input type="text" name="email" required><br>						
							</div>

							<div>
								<label>Contact:</label><br>
								<input type="number" name="contact" required><br>						
							</div>
						</div>
						
						

						<br>
						<div class="flex-add">
							<button onclick="back()" id="cancel" class="btn btn-danger">Cancel</button>
							<button type="submit" id="submit" name="add" class="btn btn-primary">Save</button>
						</div>
						
					</form>
				</div>
    		</section>

			<section id="updateSection">
				<div class="container-add">
					<h1>Manage Agent</h1>
					<hr>

					<form action="" method="post" enctype="multipart/form-data" id="addForm">
						
						<input style="display: none;" type="text" id="idData" name="id" value="">

						<div class="img-containter">
							<img src="" alt="" id="showImage-upd" width="200px">
						</div>
						<br>
						<input type="file" id="fileup" name="image" onchange="previewImageUpd(event)">
						<label for="fileup" id="fileText" class="bg-primary">Upload Image</label><br>

			

						<div style="display: flex; justify-content: space-between; gap: 1rem">
							<div>
								<label>Name:</label><br>
								<input type="text" id="name" name="name" required><br>						
							</div>
							
							<div>
								<label>Email:</label><br>
								<input type="text" id="email" name="email" required><br>						
							</div>

							<div>
								<label>Contact:</label><br>
								<input type="number" id="contact" name="contact" required><br>						
							</div>
						</div>
						
						

						<br>
						<div class="flex-add">
							<button onclick="back()" id="cancel" class="btn btn-danger">Cancel</button>
							<button type="submit" id="submit" name="update" class="btn btn-primary">Save</button>
						</div>
						
					</form>
				</div>
    		</section>

		
		<!-- jQuery -->
        <script src="assets/js/jquery-3.2.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Slimscroll JS -->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		
		<!-- Datatables JS -->
		<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
		<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
		<script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
		
		<script src="assets/plugins/datatables/dataTables.select.min.js"></script>
		
		<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
		<script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
		<script src="assets/plugins/datatables/buttons.html5.min.js"></script>
		<script src="assets/plugins/datatables/buttons.flash.min.js"></script>
		<script src="assets/plugins/datatables/buttons.print.min.js"></script>
		
		<!-- Custom JS -->
		<script  src="assets/js/script.js"></script>
		
		<script>
			let addSection = document.querySelector("#addSection");
			let updateSection = document.querySelector("#updateSection");

			function add() {
				addSection.style.display = "flex";
			}

			function edit(id, username, email, contact, uimage) {
				updateSection.style.display = 'flex';	

				document.getElementById('idData').value = id;
				document.getElementById('name').value = username;
				document.getElementById('email').value = email;
				document.getElementById('contact').value = contact;

				document.getElementById('showImage-upd').src = 'user/' + uimage;
			}

			let addForm = document.querySelector('#addForm');
			let updateForm = document.querySelector('#updateForm');

			function back() {
				window.location = "useragent.php";
				addForm.reset();
				updateForm.reset();
			}

			function previewImage(event) {
				var file = event.target.files[0];
				if (file && file.type.match('image.*')) {
					var reader = new FileReader();
					reader.onload = function() {
						var output = document.getElementById('showImage');
						output.src = reader.result;
					};
					reader.readAsDataURL(file);
				} else {
					alert('Please upload a valid image file!');
				}
			}

			function previewImageUpd(event) {
				var file = event.target.files[0];
				if (file && file.type.match('image.*')) {
					var reader = new FileReader();
					reader.onload = function() {
						var output = document.getElementById('showImage-upd');
						output.src = reader.result;
					};
					reader.readAsDataURL(file);
				} else {
					alert('Please upload a valid image file!');
				}
			}
		</script>

    </body>
</html>
