<?php
	session_start();
	require_once "./functions/database_functions.php";
	$conn = db_connect();

	// Get publisher ID from URL
	if(isset($_GET['pubid'])){
		$pubid = $_GET['pubid'];
	} else {
		echo "No publisher ID provided!";
		exit;
	}

	// Fetch books for the specific publisher
	$query = "SELECT * FROM books WHERE publisherid = $pubid";
	$result = mysqli_query($conn, $query);
	if(!$result){
		echo "Can't retrieve books " . mysqli_error($conn);
		exit;
	}
	if(mysqli_num_rows($result) == 0){
		echo "No books found for this publisher.";
		exit;
	}

	// Fetch publisher name to display
	$query_pub = "SELECT publisher_name FROM publisher WHERE publisherid = $pubid";
	$result_pub = mysqli_query($conn, $query_pub);
	$row_pub = mysqli_fetch_assoc($result_pub);
	$publisher_name = $row_pub['publisher_name'];

	$title = "Books by $publisher_name";
	require "./template/header.php";
?>

	<h5 class="fw-bolder text-center"><?php echo "Books by " . $publisher_name; ?></h5>
	<hr>
	<div class="list-group">
		<?php 
			// Loop through the books of the selected publisher
			while($book = mysqli_fetch_assoc($result)){
		?>
		<a class="list-group-item list-group-item-action" href="book_details.php?isbn=<?php echo $book['book_isbn']; ?>">
			<?php echo $book['book_title'] . " by " . $book['book_author']; ?>
		</a>
		<?php } ?>
	</div>

<?php
	mysqli_close($conn);
	require "./template/footer.php";
?>
