<?php
include("config.php");

if (isset($_GET['user']) && isset($_GET['amount'])) {
    $user = $_GET['user'];
    $amount = $_GET['amount'];

    $sql = "INSERT INTO payment (uid, amount) VALUES ('$user', '$amount')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $msg="<p class='alert alert-success'>Payment Successfully Saved</p>";
	    header("Location:feature.php?msg=$msg");
    } else {
        echo "Error storing payment details. Please contact support.";
    }
} else {
    echo "Invalid payment details. Please contact support.";
}
?>
