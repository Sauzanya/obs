<?php
  session_start();
  $count = 0;
  // Connect to the database
  $title = "Home";
  require_once "./template/header.php";
  require_once "./functions/database_functions.php";
  $conn = db_connect();
  $row = select4LatestBook($conn);

  // Search functionality (optional: show if search term exists)
  $searchTerm = $_GET['title'] ?? '';  // Retrieve search term from URL query

  // If a search term is provided, fetch books based on the search query
  if ($searchTerm !== '') {
      $sql = "SELECT * FROM books WHERE title LIKE ? ORDER BY title ASC";
      $stmt = $conn->prepare($sql);
      $searchTermWithWildcards = "%" . $searchTerm . "%";
      $stmt->bind_param("s", $searchTermWithWildcards);
      $stmt->execute();
      $searchResults = $stmt->get_result();
  }
?>

<!-- Main Content: Place Search Form at the Top -->
<div class="container">
    <!-- Search Form Section (Placed at the Top) -->
    <div class="search-container text-center my-4">
        <form action="index.php" method="get">
            <input type="text" name="title" placeholder="Search for a book" value="<?php echo htmlspecialchars($searchTerm); ?>" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Display search results if a search term is given -->
    <?php if ($searchTerm !== ''): ?>
        <div class="lead text-center text-dark fw-bolder h4">Search Results for: "<?php echo htmlspecialchars($searchTerm); ?>"</div>
        <div class="row">
            <?php
            if (isset($searchResults) && $searchResults->num_rows > 0) {
                while ($book = $searchResults->fetch_assoc()) {
                    echo "<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2'>";
                    echo "<a href='book.php?bookisbn=" . $book['book_isbn'] . "' class='card rounded-0 shadow book-item text-reset text-decoration-none'>";
                    echo "<div class='img-holder overflow-hidden'>";
                    echo "<img class='img-top' src='./bootstrap/img/" . $book['book_image'] . "'>";
                    echo "</div><div class='card-body'><div class='card-title fw-bolder h5 text-center'>" . htmlspecialchars($book['book_title']) . "</div></div></a></div>";
                }
            } else {
                echo "<p>No books found matching your search.</p>";
            }
            ?>
        </div>
    <?php endif; ?>

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
                        <img class="img-top" src="./bootstrap/img/<?php echo $book['book_image']; ?>">
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
  if (isset($conn)) {
      mysqli_close($conn);
  }
  require_once "./template/footer.php";
?>
