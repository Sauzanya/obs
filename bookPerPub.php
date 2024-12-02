<?php
session_start();
require_once "./functions/database_functions.php";

// Validate the publisherid from URL
if (isset($_GET['pubid']) && is_numeric($_GET['pubid'])) {
    $publisherid = $_GET['pubid'];  // Use the publisherid from the URL
} else {
    echo "Wrong query! Check again!";
    exit;  // Stop execution if publisherid is not provided or is invalid
}

// Connect to the database
$conn = db_connect();
$publisherName = getPublisherName($conn, $publisherid);  // Fetch publisher name based on publisherid

// Debugging output
echo "Publisher ID: " . $publisherid; // Debugging line
echo "Publisher Name: " . $publisherName; // Debugging line

// SQL query to fetch books by publisherid
$query = "SELECT book_isbn, book_title, book_image, book_descr FROM books WHERE publisherid = '$publisherid'";
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}

if (mysqli_num_rows($result) == 0) {
    echo "No books available for this publisher!";
    exit;
}

$title = "Books Per Publisher";
require "./template/header.php";
?>
<style>
    .book-item .img-holder {
        height: 20em;
    }
    .book-item:nth-child(even){
        direction: rtl !important;
    }
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="publisher_list.php" class="text-decoration-none text-muted fw-light">Publishers</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $publisherName; ?></li>
    </ol>
</nav>

<div id="pubBooks">
<?php 
    while ($row = mysqli_fetch_assoc($result)) { 
?>
    <div class="row book-item mb-2">
        <div class="col-md-3">
            <div class="img-holder overflow-hidden">
                <img class="img-top" src="./bootstrap/img/<?php echo $row['book_image']; ?>" alt="Book Image">
            </div>
        </div>
        <div class="col-md-9">
            <h4><?php echo $row['book_title']; ?></h4>
            <hr>
            <p class="truncate-5"><?= $row['book_descr'] ?></p>
            <a href="book.php?bookisbn=<?php echo $row['book_isbn']; ?>" class="btn btn-primary">Get Details</a>
        </div>
    </div>
<?php
    }
?>
</div>

<?php
if (isset($conn)) { 
    mysqli_close($conn); 
}
require "./template/footer.php";
?>
