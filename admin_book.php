<?php
// Start session
session_start();

// Include required files
require_once "./functions/admin.php"; // Admin-specific functions
require_once "./template/header.php"; // Header template
require_once "./functions/database_functions.php"; // Database functions

// Set the page title
$title = "Book List";

// Connect to the database
$conn = db_connect();

// Fetch all books from the database
$result = getAll($conn);

// Check if the query was successful
if ($result) {
    $num_rows = mysqli_num_rows($result);
} else {
    die("Error fetching books: " . mysqli_error($conn)); // Handle query errors
}
?>

<!-- Page Content -->
<h4 class="fw-bolder text-center">Book List</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<!-- Success Message -->
<?php if (isset($_SESSION['book_success'])): ?>
    <div class="alert alert-success rounded-0">
        <?= $_SESSION['book_success'] ?>
    </div>
<?php 
    unset($_SESSION['book_success']); // Clear the session message
endif;
?>

<div class="card rounded-0">
    <div class="card-body">
        <div class="container-fluid">
            <!-- Display number of books found -->
            <p><strong>Number of books found: </strong> <?php echo $num_rows; ?></p>

            <!-- Book Table -->
            <table class="table table-striped table-bordered">
                <colgroup>
                    <col width="10%">
                    <col width="15%">
                    <col width="15%">
                    <col width="10%">
                    <col width="20%">
                    <col width="10%">
                    <col width="15%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Publisher</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row and display book details
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td class="px-2 py-1 align-middle">
                            <a href="book.php?bookisbn=<?php echo $row['book_isbn']; ?>" target="_blank">
                                <?php echo $row['book_isbn']; ?>
                            </a>
                        </td>
                        <td class="px-2 py-1 align-middle"><?php echo $row['book_title']; ?></td>
                        <td class="px-2 py-1 align-middle"><?php echo $row['book_author']; ?></td>
                        <td class="px-2 py-1 align-middle">
                            <!-- Display book image -->
                            <img src="bootstrap/img/<?php echo $row['book_image']; ?>" alt="Book Image" width="50">
                        </td>
                        <td class="px-2 py-1 align-middle">
                            <p class="text-truncate" style="width:15em"><?php echo $row['book_descr']; ?></p>
                        </td>
                        <td class="px-2 py-1 align-middle"><?php echo $row['book_price']; ?></td>
                        <td class="px-2 py-1 align-middle">
                            <?php echo getPubName($conn, $row['publisherid']); ?>
                        </td>
                        <td class="px-2 py-1 align-middle text-center">
                            <!-- Action Buttons -->
                            <div class="btn-group btn-group-sm">
                                <a href="admin_edit.php?bookisbn=<?php echo $row['book_isbn']; ?>" class="btn btn-sm rounded-0 btn-primary" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="admin_delete.php?bookisbn=<?php echo $row['book_isbn']; ?>" class="btn btn-sm rounded-0 btn-danger" title="Delete" onclick="if(confirm('Are you sure to delete this book?') === false) event.preventDefault()">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Close database connection
if (isset($conn)) {
    mysqli_close($conn);
}

// Include footer template
require_once "./template/footer.php";
?>
