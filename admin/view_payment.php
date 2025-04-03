<?php include 'config.php' ?>

<?php 
$tenants =$con->query("SELECT t.*,h.pid,h.title,h.price FROM user t inner join property h on h.pid = t.house_rented where t.uid = {$_GET['id']} AND h.pid != 0 ");


foreach($tenants->fetch_array() as $k => $v) {
	if(!is_numeric($k)){
		$$k = $v;
	}
}

$paid = $con->query("SELECT SUM(amount) as paid FROM payment where uid =".$uid);
$last_payment = $con->query("SELECT * FROM payment where uid =".$uid." order by unix_timestamp(date_created) desc limit 1");
$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
$last_payment = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';


?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-4">
				<div id="details">
					<large><b>Details</b></large>
					<hr>
					<p>Tenant: <b><?php echo ucwords($uname) ?></b></p>
                    <p>House Rented: <b><?php echo $pid . " - " . $title ?></b></p>
					<p>Monthly Rental Rate: <b><?php echo number_format($price,2) ?></b></p>
					
				</div>
			</div>
			<div class="col-md-8">
				<large><b>Payment List</b></large>
					<hr>
				<table class="table table-condensed table-striped">
					<thead>
						<tr>
							<th>Date</th>
							<th>Invoice</th>
							<th style="text-align: right;">Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$payments = $con->query("SELECT * FROM payment where uid = $uid");
						if($payments->num_rows > 0):
						while($row=$payments->fetch_assoc()):
						?>
					<tr>
						<td><?php echo date("M d, Y",strtotime($row['date_created'])) ?></td>
						<td><?php echo $row['invoice'] ?></td>
						<td class='text-right'><?php echo number_format($row['amount'],2) ?></td>
					</tr>
					<?php endwhile; ?>
					<?php else: ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<style>
	#details p {
		margin: unset;
		padding: unset;
		line-height: 1.3em;
	}
	
</style>