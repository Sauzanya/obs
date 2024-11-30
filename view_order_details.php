<?php
	session_start();
	require_once "./functions/database_functions.php";
	$conn = db_connect();

	// Get the order ID from URL
	if(isset($_GET['orderid'])){
		$orderid = $_GET['orderid'];
	} else {
		echo "Order ID not provided!";
		exit;
	}

	// Query to get order details from orders table
	$query = "SELECT * FROM orders WHERE order_id = $orderid";
	$result = mysqli_query($conn, $query);
	if(!$result){
		echo "Can't retrieve order details: " . mysqli_error($conn);
		exit;
	}
	$order = mysqli_fetch_assoc($result);

	// Query to get items for this specific order
	$query_items = "SELECT * FROM order_items WHERE order_id = $orderid";
	$result_items = mysqli_query($conn, $query_items);
	if(!$result_items){
		echo "Can't retrieve order items: " . mysqli_error($conn);
		exit;
	}

	$title = "Order Details - Order #" . $orderid;
	require "./template/header.php";
?>

	<h5 class="fw-bolder text-center">Order Details for Order #<?php echo $orderid; ?></h5>
	<hr>
	<div class="row">
		<div class="col-md-6">
			<h6><strong>Customer Info:</strong></h6>
			<p>Name: <?php echo $order['customer_name']; ?></p>
			<p>Address: <?php echo $order['address']; ?></p>
			<p>Contact: <?php echo $order['contact']; ?></p>
		</div>
		<div class="col-md-6">
			<h6><strong>Order Summary:</strong></h6>
			<p>Total Items: <?php echo $order['total_items']; ?></p>
			<p>Total Price: <?php echo "Rs " . number_format($order['total_price'], 2); ?></p>
			<p>Order Date: <?php echo $order['order_date']; ?></p>
		</div>
	</div>

	<h6 class="fw-bold text-center">Ordered Items</h6>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Book ISBN</th>
				<th>Book Title</th>
				<th>Author</th>
				<th>Quantity</th>
				<th>Price per Item</th>
				<th>Total Price</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				// Loop through each order item
				while($item = mysqli_fetch_assoc($result_items)){
					// Fetch book details using the ISBN (join with books table)
					$query_book = "SELECT * FROM books WHERE book_isbn = '{$item['book_isbn']}'";
					$result_book = mysqli_query($conn, $query_book);
					$book = mysqli_fetch_assoc($result_book);
			?>
			<tr>
				<td><?php echo $item['book_isbn']; ?></td>
				<td><?php echo $book['book_title']; ?></td>
				<td><?php echo $book['book_author']; ?></td>
				<td><?php echo $item['quantity']; ?></td>
				<td><?php echo "Rs " . number_format($item['item_price'], 2); ?></td>
				<td><?php echo "Rs " . number_format($item['quantity'] * $item['item_price'], 2); ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

<?php
	mysqli_close($conn);
	require "./template/footer.php";
?>