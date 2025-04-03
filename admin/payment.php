<?php
session_start();
require("config.php");
////code
 
if(!isset($_SESSION['auser']))
{
	header("location:index.php");
}

if (isset($_POST['add'])) {

	$user = $_POST['user'];
	$invoice = $_POST['invoice'];
	$amount = $_POST['amount'];


	$sql="INSERT INTO payment (uid,invoice,amount) VALUES('$user','$invoice','$amount')";
	$result=mysqli_query($con,$sql);

	$msg="<p class='alert alert-success'>Payment Successfully Saved</p>";
	header("Location:payment.php?msg=$msg");

}

if (isset($_POST['update'])) {

	$id = $_POST['id'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$contact = $_POST['contact'];

	$pass = sha1($pass);

	$getPrevUsername = "SELECT auser FROM admin WHERE aid = $id";
	$getPrevResult = mysqli_query($con, $getPrevUsername);

    $row = mysqli_fetch_assoc($getPrevResult);
    $prevUsername = $row['auser'];
	
	if ($prevUsername == $username) {
		$sql="UPDATE admin SET auser = '$username', aemail = '$email', aphone = '$contact' WHERE aid = '$id'";
		$result=mysqli_query($con,$sql);

		$msg="<p class='alert alert-success'>Admin Updated</p>";
		header("Location:adminlist.php?msg=$msg");
	}
	else {
		$query = "SELECT * FROM admin where auser='$username'";
		$res=mysqli_query($con, $query);
		$num=mysqli_num_rows($res);

		if($num == 1)
		{
			$error = "<p class='alert alert-danger'>Email Id already Exist</p> ";
			header("Location:adminlist.php?msg=$error");
		}
		else {
			$sql="UPDATE admin SET auser = '$username', aemail = '$email', aphone = '$contact' WHERE aid = '$id'";
			$result=mysqli_query($con,$sql);

			$msg="<p class='alert alert-success'>Admin Updated</p>";
			header("Location:adminlist.php?msg=$msg");
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
								<h3 class="page-title">Payment</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
									<li class="breadcrumb-item active">Payment</li>
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
									<h4 class="card-title">Payment List</h4>
									
									</div>
									

									<button id="add" onclick="add()" class="btn btn-primary">+ Add Payment</button>
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
                                                    <th>Date</th>
                                                    <th>Tenant</th>
                                                    <th>Invoice</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        
                                        
                                            <tbody>
											<?php
													
												$query=mysqli_query($con,"select * from payment");
												
												while($row=mysqli_fetch_assoc($query))
													{
											?>
                                                <tr>

													<?php
														$queryname=mysqli_query($con,"select * from user where uid = $row[uid]");
														$rowname=mysqli_fetch_assoc($queryname);
													?>

                                                    <td><?php echo $row['id']; ?></td>
                                                    <td><?php echo date("M d, Y",strtotime($row['date_created'])) ?></td>
                                                    <td><?php echo $rowname['uname']; ?></td>
                                                    <td><?php echo $row['invoice']; ?></td>
                                                    <td><?php echo $row['amount']; ?></td>
                                                    
                                                    <td>
														
														<a href="paymentdelete.php?id=<?php echo $row['id']; ?>"><button class="btn btn-danger">Delete</button></a>
													</td>
                                                </tr>
                                                <?php
												
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
				<div class="container-add" style="width: 50%;">
					<h1>New Payment</h1>
					<hr>

					<form action="" method="post" enctype="multipart/form-data" id="addForm">
			
						<div style="display: flex; justify-content: space-between; gap: 1rem; width: 100%">
							<div style="width: 100%">
								<label>Tenant:</label><br>	
								<select class="form-control" required name="user">
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
						</div>
                        <br>
						<div style="display: flex; justify-content: space-between; gap: 1rem; width: 100%"">
							<div style="width: 100%">
								<label>Invoice:</label><br>
								<input style="width: 100%;" type="text" name="invoice" required><br>						
							</div>
						</div>
                        <br>
                        <div style="display: flex; justify-content: space-between; gap: 1rem; width: 100%"">
							<div style="width: 100%">
								<label>Amount:</label><br>
								<input style="width: 100%;" type="number" name="amount" required><br>						
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

			

			let addForm = document.querySelector('#addForm');
			function back() {
				window.location = "payment.php";
				addForm.reset();
				
			}

		</script>
    </body>
</html>
