<?php
	session_start();
	require_once "./functions/database_functions.php";
	$conn = db_connect();

	// Query to fetch all orders and their details
	$query = "SELECT * FROM orders ORDER BY order_id DESC";
	$result = mysqli_query($conn, $query);
	if(!$result){
		echo "Can't retrieve orders: " . mysqli_error($conn);
		exit;
	}

	$title = "Admin - View Orders";
	require "./template/header.php";
?>

	<h5 class="fw-bolder text-center">Orders List</h5>
	<hr>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Order ID</th>
					<th>Customer Name</th>
					<th>Address</th>
					<th>Contact</th>
					<th>Total Items</th>
					<th>Total Price</th>
					<th>Order Date</th>
					<th>View Order</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					// Loop through each order
					while($order = mysqli_fetch_assoc($result)){
				?>
				<tr>
					<td><?php echo $order['order_id']; ?></td>
					<td><?php echo $order['customer_name']; ?></td>
					<td><?php echo $order['address']; ?></td>
					<td><?php echo $order['contact']; ?></td>
					<td><?php echo $order['total_items']; ?></td>
					<td><?php echo "Rs " . number_format($order['total_price'], 2); ?></td>
					<td><?php echo $order['order_date']; ?></td>
					<td><a href="view_order_details.php?orderid=<?php echo $order['order_id']; ?>" class="btn btn-info">View</a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

<?php
	mysqli_close($conn);
	require "./template/footer.php";
?>
