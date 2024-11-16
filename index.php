<?php
session_start();
$count = 0;
// Connect to the database
$title = "Home";
require_once "./template/header.php";
require_once "./functions/database_functions.php"; // Ensure this is included

// Connect to the database
$conn = db_connect();

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the latest 4 books from the database using the new function
$row = select4LatestBook($conn);
?>

<!-- Main Content: Place Search Form at the Top -->
<div class="container">
    <!-- Search Form Section (Placed at the Top) -->
    <div class="search-container text-center my-4">
        <form action="search.php" method="get">
            <input type="text" name="title" placeholder="Search for a book" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Latest Books Section -->
    <div class="lead text-center text-dark fw-bolder h4">Latest books</div>
    <center>
        <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
    </center>
    <div class="row">
        <?php foreach($row as $book) { ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
                <a href="book.php?bookisbn=<?php echo $book['book_isbn']; ?>" class="card rounded-0 shadow book-item text-reset text-decoration-none">
                    <div class="img-holder overflow-hidden">
                        <?php
                        // Check if the book image exists in the correct folder
                        $image_path = './bootstrap/img/' . $book['book_image'];

                        // If the file does not exist, use a default image
                        if (!file_exists($image_path) || empty($book['book_image'])) {
                            $book_image = 'default.jpg'; // Default image if the actual image doesn't exist
                        } else {
                            $book_image = $book['book_image'];
                        }
                        ?>
                        <img class="img-top" src="<?php echo './bootstrap/img/' . $book_image; ?>" alt="Book Image" width="100%">
                    </div>
                    <div class="card-body">
                        <div class="card-title fw-bolder h5 text-center"><?= htmlspecialchars($book['book_title']) ?></div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<?php
// Close the database connection if it exists
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
