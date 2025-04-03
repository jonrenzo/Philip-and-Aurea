<header id="header" class="transparent-header-modern fixed-header-bg-white w-100">
            
            <div class="main-nav secondary-nav hover-success-nav py-2">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <nav class="navbar navbar-expand-lg navbar-light p-0"> <a class="navbar-brand position-relative" href="index.php"><h1>P&A</h1></a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav ml-auto">
                                        <li class="nav-item dropdown"> <a class="nav-link" href="index.php" role="button" aria-haspopup="true" aria-expanded="false">Home</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="about.php">About</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="contact.php">Contact</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="property.php">Properties</a> </li>

                                        <?php
                                        if(isset($_SESSION['uemail'])) {
                                            $paymentStatus = isset($_SESSION['paymentStatus']) ? $_SESSION['paymentStatus'] : '';
                                            if($paymentStatus == "paid"){
                                                $badgeColor = 'success';
                                            } elseif ($paymentStatus == "unpaid"){
                                                $badgeColor = 'danger';
                                            } else {
                                                $badgeColor = 'warning';
                                            }
                                            ?>
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    My Account
                                                    <span class="position-absolute top-20 start-500 translate-middle p-1 bg-<?php echo $badgeColor; ?> border border-light rounded-circle">
                                                         <span class="visually-hidden"></span>
                                                    </span>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li class="nav-item"> <a class="nav-link" href="profile.php">Profile</a> </li>
                                                    <!-- <li class="nav-item"> <a class="nav-link" href="request.php">Property Request</a> </li> -->
                                                    <li class="nav-item"> <a class="nav-link" href="feature.php">
                                                            Your Property
                                                            <span class="position-absolute top-20 start-500 translate-middle p-1 bg-<?php echo $badgeColor; ?> border border-light rounded-circle">
                                                                <span class="visually-hidden"></span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item"> <a class="nav-link" href="payment_history.php">Payment History</a>
                                                    <li class="nav-item"> <a class="nav-link" href="schedule.php">Schedule Event</a>
                                                    <li class="nav-item"> <a class="nav-link" href="messages.php">Messages</a>
                                                    <li class="nav-item"> <a class="nav-link" href="logout.php">Logout</a> </li>
                                                </ul>
                                            </li>
                                            <?php
                                        } else {
                                            ?>
                                            <li class="nav-item"> <a class="nav-link" href="login.php">Login/Register</a> </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    
									
									
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>