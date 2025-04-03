<?php
session_start();
require("config.php");
require_once "../include/message_system.php";
if (!isset($_SESSION['auser'])) {
    header('Location: index.php');
    exit();
}
$messageSystem = new MessageSystem($con);
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
                <div class="page-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="page-title">Inbox</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Messages</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="full-row bg-gray">
                    <div class="container">
                        <?php
                        $unreadCount = $messageSystem->getAdminUnreadCount();
                        if($unreadCount > 0) {
                            echo "<div class='alert alert-warning text-center'>You have $unreadCount unread message(s)</div>";
                        }

                        $messages = $messageSystem->getAdminMessages();
                        if (empty($messages)) {
                            echo '<div class="alert alert-info">No messages found.</div>';
                        } else {
                            foreach($messages as $message) {
                                $statusClass = $message['status'] === 'unread' ? 'border-warning' :
                                    ($message['status'] === 'replied' ? 'border-success' : 'border-info');
                                ?>
                                <div class="message-card mb-4 border-left <?php echo $statusClass; ?>" style="border-left-width: 4px;">
                                    <div class="card-header p-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5><?php echo htmlspecialchars($message['subject']); ?></h5>
                                                <span class="badge <?php echo ($message['status'] === 'unread' ? 'badge-warning' : 'badge-success'); ?>">
                                        <?php echo ucfirst($message['status']); ?>
                                    </span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <p>From: <?php echo htmlspecialchars($message['uemail']); ?></p>
                                                <small><?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                                        <?php if ($message['admin_reply']) { ?>
                                            <div class="admin-reply mt-4 bg-light p-3">
                                                <h6>Admin Reply:</h6>
                                                <p><?php echo nl2br(htmlspecialchars($message['admin_reply'])); ?></p>
                                                <small>Replied on <?php echo date('M j, Y g:i A', strtotime($message['replied_at'])); ?></small>
                                            </div>
                                        <?php } else { ?>
                                            <form class="admin-reply-form" method="POST" action="process-reply.php">
                                                <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                                <div class="form-group">
                                                    <textarea class="form-control" name="reply" rows="3" required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Send Reply</button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } } ?>
                    </div>
                </div>
            </div>
        </div>

			<!-- /Main Wrapper -->

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
    </body>
</html>
