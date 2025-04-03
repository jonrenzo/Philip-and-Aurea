<?php
global $con;
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
	$house = $_POST['house'];

	$img_name = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];

	$query = "SELECT * FROM user where uemail='$email'";
	$res=mysqli_query($con, $query);
	$num=mysqli_num_rows($res);

	if($num == 1) {
		$error = "<p class='alert alert-danger'>Email already Exist</p> ";
		header("Location:userlist.php?msg=$error");
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

				$sql="INSERT INTO user (uname,uemail,uphone,utype,uimage, house_rented) VALUES ('$name','$email','$contact','user','$new_img_name', $house)";
				$result=mysqli_query($con, $sql);

				$sqlhouse="UPDATE property SET status = 'Sold' WHERE pid = $house";
				$resulthouse=mysqli_query($con, $sqlhouse);

				if($result){
					$msg = "<p class='alert alert-success'>User Added Successfully</p> ";
				}
				else{
					$msg = "<p class='alert alert-warning'>Register Not Successfully</p> ";
				}

				header("Location:userlist.php?msg=$msg");
			}

		}
	}

}

if (isset($_POST['update'])) {
	
	$id = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$contact = $_POST['contact'];
	$house = $_POST['house'];

	$img_name = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];

	
	$getPrevEmail = "SELECT uemail, house_rented FROM user WHERE uid = $id";
	$getPrevResult = mysqli_query($con, $getPrevEmail);

    $row = mysqli_fetch_assoc($getPrevResult);
    $prevUserEmail = $row['uemail'];
	$prevUserHouse = $row['house_rented'];


	if ($prevUserEmail == $email && $img_name == '') {
		$sql="UPDATE user SET uname = '$name', uemail = '$email', uphone = '$contact', house_rented = $house WHERE uid = '$id'";
		$result=mysqli_query($con,$sql);

		if ($prevUserHouse == "") {

		}
		else if ($prevUserHouse != $house) {
			$sqlhouse="UPDATE property SET status = 'available' WHERE pid = $prevUserHouse";
			$resulthouse=mysqli_query($con, $sqlhouse);
		}
		$sqlhouse="UPDATE property SET status = 'Sold' WHERE pid = $house";
		$resulthouse=mysqli_query($con, $sqlhouse);
		
		$msg="<p class='alert alert-success'>User Updated</p>";
		header("Location:userlist.php?msg=$msg");
	}
	elseif ($prevUserEmail == $email && $img_name != '') {
		if ($error === 0) {

			$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png", "HEIC"); 

			if (in_array($img_ex_lc, $allowed_exs)) {

				$new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                $img_upload_path = 'user/'.$new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);

				$sql="UPDATE user SET uname = '$name', uemail = '$email', uphone = '$contact', uimage = '$new_img_name', house_rented = $house WHERE uid = '$id'";
				$result=mysqli_query($con,$sql);

				if($result){
					if ($prevUserHouse != $house) {
						$sqlhouse="UPDATE property SET status = 'available' WHERE pid = $prevUserHouse";
						$resulthouse=mysqli_query($con, $sqlhouse);
					}
					$sqlhouse="UPDATE property SET status = 'Sold' WHERE pid = $house";
					$resulthouse=mysqli_query($con, $sqlhouse);

					$msg = "<p class='alert alert-success'>User Updated Successfully</p> ";
				}
				else{
					$msg = "<p class='alert alert-warning'>Register Not Successfully</p> ";
				}

				header("Location:userlist.php?msg=$msg");
			}

		}
	}
	else {
		$query = "SELECT * FROM user where uemail='$email'";
		$res=mysqli_query($con, $query);
		$num=mysqli_num_rows($res);

		if($num == 1) {
			$error = "<p class='alert alert-danger'>Email Id already Exist</p> ";
			header("Location:userlist.php?msg=$error");
		}
		else {
			if ($img_name != '') {
				if ($error === 0) {

					$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
					$img_ex_lc = strtolower($img_ex);
		
					$allowed_exs = array("jpg", "jpeg", "png", "HEIC"); 
		
					if (in_array($img_ex_lc, $allowed_exs)) {
		
						$new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
						$img_upload_path = 'user/'.$new_img_name;
						move_uploaded_file($tmp_name, $img_upload_path);
		
						$sql="UPDATE user SET uname = '$name', uemail = '$email', uphone = '$contact', uimage = '$new_img_name', house_rented = $house WHERE uid = '$id'";
						$result=mysqli_query($con,$sql);
		
						if($result){
							$msg = "<p class='alert alert-success'>User Updated Successfully</p> ";
						}
						else{
							$msg = "<p class='alert alert-warning'>Update Not Successfully</p> ";
						}
	
						if ($prevUserHouse != $house) {
							$sqlhouse="UPDATE property SET status = 'available' WHERE pid = $prevUserHouse";
							$resulthouse=mysqli_query($con, $sqlhouse);
						}
						$sqlhouse="UPDATE property SET status = 'Sold' WHERE pid = $house";
						$resulthouse=mysqli_query($con, $sqlhouse);
		
						header("Location:userlist.php?msg=$msg");
					}
		
				}
			}
			else {
				$sql="UPDATE user SET uname = '$name', uemail = '$email', uphone = '$contact', house_rented = $house WHERE uid = '$id'";
				$result=mysqli_query($con,$sql);

				if($result){
					$msg = "<p class='alert alert-success'>User Updated Successfully</p> ";
				}
				else{
					$msg = "<p class='alert alert-warning'>Update Not Successfully</p> ";
				}

				if ($prevUserHouse != $house) {
					$sqlhouse="UPDATE property SET status = 'available' WHERE pid = $prevUserHouse";
					$resulthouse=mysqli_query($con, $sqlhouse);
				}
				$sqlhouse="UPDATE property SET status = 'Sold' WHERE pid = $house";
				$resulthouse=mysqli_query($con, $sqlhouse);

				header("Location:userlist.php?msg=$msg");
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
								<h3 class="page-title">User</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
									<li class="breadcrumb-item active">User</li>
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
										<h4 class="card-title">User List</h4>
									</div>
									

									<button id="add" onclick="add()" class="btn btn-primary">+ Add User</button>
									
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
                                                    <th>House Rented</th>
                                                    <th>Monthly Rate</th>
                                                    <th>Last Payment</th>
                                                    
                                                    <th>Email</th>
                                                    <th>Contact</th>
													<th>Image</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        
                                        
                                            <tbody>
											<?php
											$query = mysqli_query($con, "SELECT * FROM user WHERE utype='user'");
											$cnt = 1;
											while ($row = mysqli_fetch_assoc($query)) {
												$paid = $con->query("SELECT SUM(amount) as paid FROM payment WHERE uid =".$row['uid']);
												$last_payment = $con->query("SELECT * FROM payment WHERE uid =".$row['uid']." ORDER BY unix_timestamp(date_created) DESC LIMIT 1");
												$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
												$last_payment = $last_payment->num_rows > 0 ? date("M d, Y", strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';

												$stmt = $con->prepare("SELECT * FROM property WHERE pid = ?");
												$stmt->bind_param("i", $row['house_rented']);
												$stmt->execute();
												$price = $stmt->get_result();
												$price = $price->num_rows > 0 ? $price->fetch_array()['price'] : 0;

												$stmt = $con->prepare("SELECT * FROM property WHERE pid = ?");
												$stmt->bind_param("i", $row['house_rented']);
												$stmt->execute();
												$title = $stmt->get_result();
												$titleRow = mysqli_fetch_assoc($title);
											?>
												<tr>
													<td><?php echo $cnt; ?></td>
													<td><?php echo $row['uname']; ?></td>
													<td><?php echo $row['house_rented'] ?: 'N/A'; if ($titleRow) echo ' - ' . $titleRow['title']; ?></td>
													<td><?php echo number_format($price); ?></td>
													<td><?php echo $last_payment; ?></td>
													<td><?php echo $row['uemail']; ?></td>
													<td><?php echo $row['uphone']; ?></td>
													<td><img src="user/<?php echo $row['uimage']; ?>" height="50px" width="50px"></td>
													<td>
														<button class="btn btn-primary text-white view_payment" type="button" data-id1="<?php echo $row['uid']; ?>"
																data-id2="<?php echo $row['house_rented'] ?: 0; ?>">
															View
														</button>
														<a <?php echo "onclick='edit(
															{$row['uid']},
															\"" . addslashes($row['uname']) . "\", 
															\"" . addslashes($row['uemail']) . "\", 
															\"" . addslashes($row['uphone']) . "\", 
															" . ($row['house_rented'] ?? 'null') . ", 
															\"" . addslashes($row['uimage']) . "\")'"; ?> 
															class="btn btn-primary text-white">Edit
														</a>
														<a href="userdelete.php?id=<?php echo $row['uid']; ?>">
															<button class="btn btn-danger">Delete</button>
														</a>
													</td>
												</tr>
											<?php
												$cnt++;
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
					<h1>New User</h1>
					<hr>

					<form action="" method="post" enctype="multipart/form-data" id="addForm" style="width: 100%;">

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

							
						</div>
						<br>
						<div style="display: flex; justify-content: space-between; gap: 1rem">

							<div>
								<label>Contact:</label><br>
								<input type="number" name="contact" required><br>				
							</div>

							<div>
								<label>House Rented:</label><br>
										
								<select name="house" id="" required>
									<option value="">--Select House ID--</option>
									<?php
										$query=mysqli_query($con,"select * from property where status = 'available'");
										while($row=mysqli_fetch_assoc($query))
										{
									?>

									<option value="<?php echo $row['pid'] ?>"><?php echo $row['pid'] . " - " . $row['title'] ?></option>
									
									<?php
										}
									?>
								</select>
				
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
					<h1>Manage User</h1>
					<hr>

					<form action="" method="post" enctype="multipart/form-data" id="updateForm">
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
								<input type="text" name="name" id="name" required><br>						
							</div>
							
							<div>
								<label>Email:</label><br>
								<input type="text" name="email" id="email" required><br>				
							</div>

							
						</div>
						<br>
						<div style="display: flex; justify-content: space-between; gap: 1rem">

							<div>
								<label>Contact:</label><br>
								<input type="number" name="contact" id="contact" required><br>				
							</div>

							<div>
								<label>House Rented:</label><br>
										
								<select name="house" id="house" required>
									<option value="0">--Select House ID--</option>
									<?php
										$query=mysqli_query($con,"select * from property");
										while($row=mysqli_fetch_assoc($query))
										{
									?>

									<option value="<?php echo $row['pid'] ?>"><?php echo $row['pid'] . " - " . $row['title'] ?></option>
									
									<?php
										}
									?>
								</select>
				
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
			$(document).ready(function(){
				$('table').dataTable()
			})
			function loadModal(title, url, size) {
				
				var modal = document.createElement('div');
				modal.className = 'modal';

				
				modal.innerHTML = '<div class="modal-dialog modal-' + size + '">' +
									'<div class="modal-content">' +
										'<div class="modal-header">' +
											'<h5 class="modal-title">' + title + '</h5>' +
											'<button type="button" class="close" data-dismiss="modal">&times;</button>' +
										'</div>' +
										'<div class="modal-body"></div>' +
									'</div>' +
								'</div>';

				
				document.body.appendChild(modal);

			
				fetch(url)
					.then(response => response.text())
					.then(data => {
						modal.querySelector('.modal-body').innerHTML = data;
						$(modal).modal('show'); // 
					})
				.catch(error => console.error('Modal loading error:', error));
			}
	
			
			$('.view_payment').click(function(){
				
				if ($(this).attr('data-id2') != 0) {
					loadModal("Tenants Payments","view_payment.php?id="+$(this).attr('data-id1'),"xl");
				}
				else {
					var modal = document.createElement('div');
					modal.className = 'modal';

					
					modal.innerHTML = '<div class="modal-dialog modal-' + 'large' + '">' +
										'<div class="modal-content">' +
											'<div class="modal-header">' +
												'<h5 class="modal-title">' + 'The User has not rented yet' + '</h5>' +
												'<button type="button" class="close" data-dismiss="modal">&times;</button>' +
											'</div>' +
											'<div class="modal-body"></div>' +
										'</div>' +
									'</div>';

					
					document.body.appendChild(modal);
					$(modal).modal('show'); 
						
				}	

			})

			

			let addSection = document.querySelector("#addSection");
			let updateSection = document.querySelector("#updateSection");

			function add() {
				addSection.style.display = "flex";
			}

			function edit(uid, name, email, contact, house, uimage) {
				updateSection.style.display = 'flex';

				document.getElementById('idData').value = uid;
				document.getElementById('name').value = name;
				document.getElementById('email').value = email;
				document.getElementById('contact').value = contact;
				document.getElementById('house').value = house;

				document.getElementById('showImage-upd').src = 'user/' + uimage;
			}

			function back() {
				window.location = "userlist.php";
				addForm.reset();
				// updateForm.reset();
			}

			//PREVIEW IMAGE FUNCTION
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
