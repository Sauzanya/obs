<?php
session_start();
$count = 0;

// Title for the page
$title = "Home";

// Include necessary files
require_once "./template/header.php";
require_once "./functions/database_functions.php";

// Connect to the database
$conn = db_connect();

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the latest 4 books
$latest_books = select4LatestBook($conn);

// Fetch the top 4 recommended books based on sales
$recommended_books = selectTopSellingBooks($conn, 4);

?>
<!-- Main Content -->
<div class="container">

    <!-- Search Form Section -->
    <div class="search-container text-center my-4">
        <form action="search.php" method="get">
            <input type="text" name="title" placeholder="Search for a book" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Latest Books Section -->
    <div class="lead text-center text-dark fw-bolder h4">Latest Books</div>
    <center>
        <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
    </center>
    <div class="row">
        <?php foreach ($latest_books as $book) { ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
                <a href="book.php?bookisbn=<?php echo $book['book_isbn']; ?>" class="card rounded-0 shadow book-item text-reset text-decoration-none">
                    <div class="img-holder overflow-hidden">
                        <?php
                        // Check if the book image exists
                        $image_path = './bootstrap/img/' . $book['book_image'];
                        $book_image = (!file_exists($image_path) || empty($book['book_image'])) ? 'default.jpg' : $book['book_image'];
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

    <!-- Recommended Books Section -->
    <div class="lead text-center text-dark fw-bolder h4 mt-5">Recommended for You</div>
    <center>
        <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
    </center>
    <div class="row">
        <?php foreach ($recommended_books as $book) { ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
                <a href="book.php?bookisbn=<?php echo $book['book_isbn']; ?>" class="card rounded-0 shadow book-item text-reset text-decoration-none">
                    <div class="img-holder overflow-hidden">
                        <?php
                        // Check if the book image exists
                        $image_path = './bootstrap/img/' . $book['book_image'];
                        $book_image = (!file_exists($image_path) || empty($book['book_image'])) ? 'default.jpg' : $book['book_image'];
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

// Include the footer
require_once "./template/footer.php";
?>
