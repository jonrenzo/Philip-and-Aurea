<?php

use Google\Service\Adsense\Payment;

global $con;
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
if(!isset($_SESSION['uemail']))
{
	header("location:login.php");
}								
?>
<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="images/favicon.ico">

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

<!--	Css Link
	========================================================-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="chatbot-deployment/static/style.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/login.css">

<!--	Title
	=========================================================-->
<title>Philip and Aurea Residences</title>
</head>
<body>
<?php include("chatbot.php");?>
<!--	Page Loader
=============================================================
<div class="page-loader position-fixed z-index-9999 w-100 bg-white vh-100">
	<div class="d-flex justify-content-center y-middle position-relative">
	  <div class="spinner-border" role="status">
		<span class="sr-only">Loading...</span>
	  </div>
	</div>
</div>
-->




<div id="page-wrapper">
    <div class="row"> 
        <!--	Header start  -->
		<?php include("include/header.php");?>
        <!--	Header end  -->
        
        <!--	Banner   --->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>User Listed Property</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">User Listed Property</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
         <!--	Banner   --->
		 
		 
		<!--	Submit property   -->
        <div class="full-row bg-gray">
            <div class="container">
                    <div class="row mb-5">
						<div class="col-lg-12">
							<h2 class="text-secondary double-down-line text-center">My Property</h2>
							<?php 
								if(isset($_GET['msg']))	
								echo $_GET['msg'];	
							?>
                        </div>
					</div>
					<table class="items-list col-lg-12 table-hover" style="border-collapse:inherit;">
                        <thead>
                             <tr  class="bg-dark">
                                <th class="text-white font-weight-bolder">Property</th>
                                <th class="text-white font-weight-bolder">BHK</th>
                                <th class="text-white font-weight-bolder">Type</th>
                                <th class="text-white font-weight-bolder">Lease Date</th>
                                 <th class="text-white font-weight-bolder">Payment Status</th>
								<th class="text-white font-weight-bolder">Payment Date</th>
                                
								<th class="text-white font-weight-bolder">Pay</th>
                             </tr>  
                        </thead>
                        <tbody>
					
                            <form action="pay.php" method="post">
                            <?php 
							$pid=$_SESSION['house_rented'];
                            $uid=$_SESSION['uid'];
                            echo "<script>console.log('PHP variable: " . $uid . "');</script>";
                            
                            if ($pid) {

                            
							$query=mysqli_query($con,"SELECT * FROM property WHERE pid=$pid");
                            $paymentQuery = mysqli_query($con, "SELECT * FROM payment WHERE uid=$uid");
                           
                            
                            $paymentRow = mysqli_fetch_array($paymentQuery);
								while($row=mysqli_fetch_array($query))
								{
							?>
                            <tr>
                                <input style="display: none;" value="<?php echo $row['13'] ?>" type="number" name="amount">
                                <?php
                                    $userquery=mysqli_query($con,"SELECT * FROM user WHERE house_rented=$pid");
                                    $rowuser=mysqli_fetch_assoc($userquery)
                                ?>
                                <input style="display: none;" value="<?php echo $rowuser['uid'] ?>" type="text" name="user">
                                <td style="width: 350px">
									<img src="admin/property/<?php echo $row['18'];?>" alt="pimage">
                                    <div class="property-info d-table">
                                        <h5 class="text-secondary text-capitalize"><a href="propertydetail.php?pid=<?php echo $row['0'];?>"><?php echo $row['1'];?></a></h5>
                                        <span class="font-14 text-capitalize"><i class="fas fa-map-marker-alt text-success font-13"></i>&nbsp; <?php echo $row['14'];?></span>
                                        <div class="price mt-3">
											<span class="text-success">₱&nbsp;<?php echo $row['13'];?></span>
										</div>
                                    </div>
								</td>
                                <td><?php echo $row['bhk'];?></td>
                                <td class="text-capitalize">For <?php echo $row['stype'];?></td>
                                <td><?php echo $row['date'];?></td>
                                <td class="text-capitalize" style="text-align: center">
                                    <?php
                                    // payment status
                                    if (isset($paymentRow['uid'])) {
                                        if ($uid != $paymentRow['uid']) {
                                            $paymentStatus = 'unpaid';
                                        } elseif ($uid === $paymentRow['uid']) {
                                            $paymentStatus = 'paid';
                                        } else {
                                            $paymentStatus = 'pending';
                                        }
                                    } else {
                                        $paymentStatus = 'unpaid';
                                    }
                                    $_SESSION['paymentStatus'] = $paymentStatus;
                                    if ($paymentStatus === 'paid') {
                                        echo '<span class="badge badge-success">Paid</span>';
                                    } elseif ($paymentStatus === 'unpaid') {
                                        echo '<span class="badge badge-danger">Unpaid</span>';
                                        $showAlert = true;
                                    } else {
                                        echo '<span class="badge badge-warning">Pending</span>';
                                        $showAlert = true;
                                    }
                                    ?>
                                </td>

                                <td class="text-capitalize">
                                    <?php echo isset($paymentRow['date_created']) && !empty($paymentRow['date_created']) ? $paymentRow['date_created'] : "No Records Found"; ?>
                                </td> <!-- payment date -->


								<td><button type="submit" name='pay' class="btn btn-primary">Pay</button></td>
                            </tr>
							<?php } 
                            
                                }
                                else {
                                    echo "<tr><td colspan='8' style='text-align: center;'>No data available</td></tr>";
                                }
                                ?>
                            </form>


                        </tbody>
                    </table>            
            </div>
        </div>
	<!--	Submit property   -->
        
        <!--	Footer   start-->
		<?php include("include/footer.php");?>
		<!--	Footer   start-->
        
        <!-- Scroll to top --> 
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
        <!-- End Scroll To top --> 
    </div>




</div>
<!-- Wrapper End --> 

<!--	Js Link
============================================================-->

<script src="js/jquery.min.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/greensock.js"></script> 
<script src="js/layerslider.transitions.js"></script> 
<script src="js/layerslider.kreaturamedia.jquery.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/owl.carousel.min.js"></script> 
<script src="js/tmpl.js"></script> 
<script src="js/jquery.dependClass-0.1.js"></script> 
<script src="js/draggable-0.1.js"></script> 
<script src="js/jquery.slider.js"></script> 
<script src="js/wow.js"></script> 
<script src="js/custom.js"></script>
</body>
</html>