<?php
	session_start();
	require_once "./functions/database_functions.php";
	$conn = db_connect();

	// Fetch publishers and count the number of books per publisher using JOIN and GROUP BY
	$query = "
		SELECT publisher.publisherid, publisher.publisher_name, COUNT(books.publisherid) AS book_count 
		FROM publisher
		LEFT JOIN books ON publisher.publisherid = books.publisherid
		GROUP BY publisher.publisherid
		ORDER BY publisher.publisherid
	";
	$result = mysqli_query($conn, $query);
	if(!$result){
		echo "Can't retrieve data " . mysqli_error($conn);
		exit;
	}
	if(mysqli_num_rows($result) == 0){
		echo "Empty publisher list! Something went wrong. Please check again.";
		exit;
	}

	$title = "List Of Publishers";
	require "./template/header.php";
?>

	<div class="h5 fw-bolder text-center">List of Publishers</div>
	<hr>
	<div class="list-group">
		<a class="list-group-item list-group-item-action" href="books.php">
			List of All Books
		</a>
	<?php 
		// Loop through each publisher
		while($row = mysqli_fetch_assoc($result)){
			// Display each publisher with book count
	?>
		<a class="list-group-item list-group-item-action" href="bookPerPub.php?pubid=<?php echo $row['publisherid']; ?>">
			<span class="badge badge-primary bg-primary rounded-pill"><?php echo $row['book_count']; ?></span>
			<?php echo $row['publisher_name']; ?>
		</a>
	<?php } ?>
	</div>

<?php
	mysqli_close($conn);
	require "./template/footer.php";
?>
