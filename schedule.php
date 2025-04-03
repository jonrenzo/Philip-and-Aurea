<?php
session_start();
include("config.php");
require "vendor/autoload.php";


if(isset($_POST['submit'])) {

    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $user_id = mysqli_real_escape_string($con, $_POST['user']);
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $time_from = mysqli_real_escape_string($con, $_POST['time_from']);
    $time_to = mysqli_real_escape_string($con, $_POST['time_to']);

    $event_type_map = [
        '1' => 'Property Inspection',
        '2' => 'Maintenance Request',
        '3' => 'Lease Reviewal'
    ];
    $event_type = $event_type_map[$title] ?? 'Other';

    $start_datetime = date('Y-m-d H:i:s', strtotime("$date $time_from"));
    $end_datetime = date('Y-m-d H:i:s', strtotime("$date $time_to"));

    // Insert event into database
    $insert_query = "INSERT INTO events (
        title, 
        description, 
        start_datetime, 
        end_datetime, 
        user_id, 
        event_type
    ) VALUES (
        '$event_type', 
        '$description', 
        '$start_datetime', 
        '$end_datetime', 
        '$user_id', 
        '$event_type'
    )";

    if(mysqli_query($con, $insert_query)) {
        $_SESSION['success_message'] = "Event scheduled successfully!";
        header("Location: schedule.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error scheduling event: " . mysqli_error($con);
    }
}

// Fetch events for calendar
function fetchEvents() {
    global $con;
    $events = [];

    $query = "SELECT e.*, u.uname as tenant_name 
              FROM events e 
              LEFT JOIN user u ON e.user_id = u.uid";
    $result = mysqli_query($con, $query);

    while($row = mysqli_fetch_assoc($result)) {
        $events[] = [
            'title' => $row['title'] . ' - ' . $row['tenant_name'],
            'start' => $row['start_datetime'],
            'end' => $row['end_datetime'],
            'description' => $row['description']
        ];
    }

    return $events;
}

if(isset($_GET['get_events'])) {
    header('Content-Type: application/json');
    echo json_encode(fetchEvents());
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
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Schedule an Event</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Schedule Event</li>
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
                        <h2 class="text-secondary double-down-line text-center">Schedule an Event</h2>
                    </div>
                </div>

                <!-- Form -->
                <div class="wrapper">
                    <div class="row">
                        <!-- Form Column -->
                        <div class="col-md-6">
                            <form action="schedule.php" method="post" class="form">
                                <!-- Event Title -->
                                <div class="form-group">
                                    <label for="title" class="font-weight-bold">Title</label>
                                    <div class="input-group mb-3">
                                        <select class="custom-select" id="eventType" name="title">
                                            <option selected>Choose...</option>
                                            <option value="1">Property Inspection</option>
                                            <option value="2">Maintenance Request</option>
                                            <option value="3">Lease Reviewal</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Event Description -->
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold">Event Description</label>
                                    <textarea name="description" id="description" rows="3" class="form-control" placeholder="Description. . . "></textarea>
                                </div>
                                <!-- Event Property Location -->
                                <div class="form-group">
                                    <label for="location" class="font-weight-bold">Tenant Location</label>
                                    <select class="form-control" required name="user">
                                        <option value="">Tenant</option>

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
                                <!-- Event Date -->
                                <div class="form-group">
                                    <label for="date" class="font-weight-bold">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" required value="">
                                </div>
                                <!-- Event Time -->
                                <div class="form-group time">
                                    <label>Time</label>
                                    <input type="time" name="time_from" class="form-control mb-2" required>
                                    <span>TO</span>
                                    <input type="time" name="time_to" class="form-control mt-2" required>
                                </div>

                                <div class="form-group">
                                    <input type="submit" name="submit" id="submit" value="Schedule Event"
                                           class="btn btn-success w-100 font-weight-bold font-18">
                                </div>
                            </form>
                        </div>

                        <!-- Details Column -->
                        <div class="col-md-6">
                            <!-- Property Inspection Details -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <i class="fas fa-clipboard-check text-primary fa-lg"></i>
                                    <h4>Property Inspection</h4>
                                    <p>assessment of a unit's condition, examining structural elements and key systems
                                        to identify potential issues and ensure safety.</p>
                                </div>
                            </div>

                            <!-- Maintenance Request Details -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <i class="fas fa-wrench text-primary fa-lg"></i>
                                    <h4>Maintenance Request</h4>
                                    <p>to report property issues or repairs needed, ensuring timely resolution
                                        and proper facility upkeep.</p>
                                </div>
                            </div>

                            <!-- Lease Renewal Details -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <i class="fas fa-file-contract text-primary fa-lg"></i>
                                    <h4>Lease Renewal</h4>
                                    <p>extending an existing lease term between landlord and tenant,
                                        maintaining the rental arrangement under specified terms and conditions.</p>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <i class="fas fa-file-contract text-primary fa-lg"></i>
                                    <h4>Request a Tour</h4>
                                    <p>Interested in seeing our properties up close? Simply fill out our tour request form, and we’ll schedule a convenient time for you to explore and experience the space firsthand!</p>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="container-fluid">
                                    <div class="calendar-wrapper">
                                        </div>
                                        <div class="calendar-container">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Replace your existing calendar div with this -->
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
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/index.global.min.js'></script>
<script src="js/custom.js"></script>
</body>
</html>