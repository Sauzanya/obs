<?php
session_start();
require_once "./functions/admin.php";
$title = "List book";
require_once "./template/header.php";
require_once "./functions/database_functions.php";
require_once "./search_ALG/BST_admin.php";

// Establish database connection
$conn = db_connect();

// Fetch all books using the updated getAll() function
$result = getBooksAndPublisher($conn);
$bst = new BinarySearchTree();

// Check if the result is empty
if ($result->num_rows == 0) {
    echo "Error: No books found or an issue occurred while fetching the book list.";
    exit;  // Exit the script if no books are found
}
$books = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bst->insert($row);
    $books[] = $row;
}

$titleQuery = isset($_GET['title']) ? $_GET['title'] : '';
$authorQuery = isset($_GET['author']) ? $_GET['author'] : '';

if (!empty($titleQuery) || !empty($authorQuery)) {
    $searchResults = $bst->search($bst->root, $titleQuery, $authorQuery);
} else {
    $searchResults = $books;
}

// Pagination
$booksPerPage = 5;
$totalBooks = count($searchResults);
$totalPages = ceil($totalBooks / $booksPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startIndex = ($currentPage - 1) * $booksPerPage;
$paginatedResults = array_slice($searchResults, $startIndex, $booksPerPage);

?>

<h4 class="fw-bolder text-center">Book List</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<?php if(isset($_SESSION['book_success'])): ?>
    <div class="alert alert-success rounded-0">
        <?= $_SESSION['book_success'] ?>
    </div>
<?php 
    unset($_SESSION['book_success']);
endif;
?>

<div class="card rounded-0">
    <div class="card-body">
        <div class="container-fluid">

            <div class="container">
                <form class="search-form" method="GET" action="">
                    <input type="text" name="title" placeholder="Search by title" value="<?php echo isset($_GET['title']) ? $_GET['title'] : ''; ?>">
                    <input type="text" name="author" placeholder="Search by author" value="<?php echo isset($_GET['author']) ? $_GET['author'] : ''; ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <table class="table table-striped table-bordered">
                <!-- <colgroup>
                    <col width="15%">
                    <col width="15%">
                    <col width="10%">
                    <col width="10%">
                    <col width="15%">
                    <col width="10%">
                    <col width="15%">
                    <col width="10%">
                </colgroup> -->
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Publisher</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paginatedResults as $row): 
                        // debug($row, 1);
                        ?>
                        <tr>
                            <td class="px-2 py-1 align-middle">
                                <a href="book.php?bookisbn=<?php echo $row['book_isbn']; ?>" target="_blank">
                                    <?php echo $row['book_isbn']; ?>
                                </a>
                            </td>
                            <td class="px-2 py-1 align-middle"><?php echo $row['book_title']; ?></td>
                            <td class="px-2 py-1 align-middle"><?php echo $row['book_author']; ?></td>
                            <td class="px-2 py-1 align-middle">
                                <img src="bootstrap/img/<?php echo $row['book_image']; ?>" alt="<?php echo $row['book_title']; ?>" width="100" height="auto">
                            </td>
                            
                            <td class="px-2 py-1 align-middle"><?php echo $row['book_price']; ?></td>
                            <td class="px-2 py-1 align-middle"><?php echo $row['publisher_name']; ?></td>
                            <td class="px-2 py-1 align-middle text-center">
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
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pagination">
            <?php
                // Display pagination links
                if($totalPages > 1){
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == $currentPage) {
                            echo "<a href=\"?title={$titleQuery}&author={$authorQuery}&page={$i}\" class=\"active\">{$i}</a>";
                        } else {
                            echo "<a href=\"?title={$titleQuery}&author={$authorQuery}&page={$i}\">{$i}</a>";
                        }
                    }
                }



       
    
                
            ?>
        </div>

        </div>
    </div>
</div>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>