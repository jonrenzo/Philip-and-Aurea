<?php
session_start();
include("config.php");
require "vendor/autoload.php";
require_once 'include/message_system.php';

if (!isset($_SESSION['uid'])){
    header('Location: index.php');
    exit();
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
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Messages</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Messages</li>
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
                        <h2 class="text-secondary double-down-line text-center">Messages</h2>
                    </div>
                </div>

                <!-- Form -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="submit-property bg-white">
                            <!-- Message Form -->
                            <div class="col-lg-12 mt-4 pt-3">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#newMessageModal">
                                    <i class="fa fa-plus"> New Message</i>
                                </button>
                            </div>

                            <!-- Messages List -->
                            <div class="col-lg-12 mt-4 pb-3">
                                <?php
                                $messageSystem = new MessageSystem($con); // Assuming $con is your database connection
                                $messages = $messageSystem->getMessages($_SESSION['uid']);

                                if(empty($messages)) {
                                    echo '<div class="alert alert-info">No messages found.</div>';
                                }

                                foreach($messages as $message):
                                    ?>
                                    <div class="property-card bg-white mb-4">
                                        <div class="property-card-header p-4">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h5><?php echo htmlspecialchars($message['subject']); ?></h5>
                                                    <span class="badge <?php
                                                    echo $message['status'] === 'unread' ? 'badge-warning' :
                                                        ($message['status'] === 'replied' ? 'badge-success' : 'badge-info');
                                                    ?>">
                                        <?php echo ucfirst($message['status']); ?>
                                    </span>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <small class="text-muted">
                                                        <?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="property-card-body p-4">
                                            <div class="message-content">
                                                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                            </div>

                                            <?php if($message['admin_reply']): ?>
                                                <div class="admin-reply mt-4 p-3 bg-light">
                                                    <h6 class="text-secondary">Admin Reply:</h6>
                                                    <div class="reply-content">
                                                        <?php echo nl2br(htmlspecialchars($message['admin_reply'])); ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        Replied: <?php echo date('M j, Y g:i A', strtotime($message['replied_at'])); ?>
                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="property-card-footer p-4 border-top">
                                            <button class="btn btn-danger btn-sm delete-message" data-id="<?php echo $message['id']; ?>" onclick="console.log("test)">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Message Modal -->
                <div class="modal fade" id="newMessageModal" tabindex="-1" role="dialog" aria-labelledby="newMessageModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newMessageModalLabel">Send New Message</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="messageForm" action="process_message.php" method="POST">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Add this before the closing body tag -->
            </div>
        </div>
        <!--    Submit Property   -->

        <!--	Footer   start-->
		<?php include("include/footer.php");?>
		<!--	Footer   start-->
        
        <!-- Scroll to top --> 
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
        <!-- End Scroll To top --> 
    </div>
</div>
<!-- Wrapper End -->
<script src="js/jquery.min.js"></script>
<script>

</script>
<!--	Js Link
============================================================--> 

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