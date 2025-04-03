<?php
include("config.php");
$id = $_GET['id'];
$sql = "DELETE FROM payment WHERE id = {$id}";
$result = mysqli_query($con, $sql);
if($result == true)
{
	$msg="<p class='alert alert-success'>Payment Deleted</p>";
	header("Location:payment.php?msg=$msg");
}
else{
	$msg="<p class='alert alert-warning'>Payment Not Deleted</p>";
	header("Location:payment.php?msg=$msg");
}
mysqli_close($con);
?>
