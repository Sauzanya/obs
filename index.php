<?php
  session_start();
  $count = 0;
  
  // Set the page title
  $title = "Home";
  require_once "./template/header.php";
  require_once "./functions/database_functions.php";
  $conn = db_connect();

  // Check if there's a search query
  $searchQuery = $_GET['search'] ?? '';  // Get the search term from the URL (if provided)

  // If a search query exists, search for books by title
  if ($searchQuery) {
      $stmt = $conn->prepare("SELECT * FROM books WHERE book_title LIKE ?");
      $searchTerm = '%' . $searchQuery . '%';
      $stmt->bind_param("s", $searchTerm);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_all(MYSQLI_ASSOC);
  } else {
      // Otherwise, fetch the latest books
      $row = select4LatestBook($conn);
  }
?>

<!-- Search Form -->
<div class="container mt-4">
  <form class="d-flex" action="index.php" method="get">
    <input class="form-control me-2" type="search" name="search" placeholder="Search for a book by title" aria-label="Search" value="<?php echo htmlspecialchars($searchQuery); ?>">
    <button class="btn btn-outline-success" type="submit">Search</button>
  </form>
</div>

<!-- Example row of columns -->
<div class="lead text-center text-dark fw-bolder h4 mt-4">
  <?php echo $searchQuery ? "Search Results for \"$searchQuery\"" : "Latest Books"; ?>
</div>
<center>
  <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<div class="row">
  <?php
    if ($row && count($row) > 0) {
        foreach($row as $book) { ?>
          <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
            <a href="book.php?bookisbn=<?php echo $book['book_isbn']; ?>" class="card rounded-0 shadow book-item text-reset text-decoration-none">
              <div class="img-holder overflow-hidden">
                <img class="img-top" src="./bootstrap/img/<?php echo $book['book_image']; ?>">
              </div>
              <div class="card-body">
                <div class="card-title fw-bolder h5 text-center"><?= htmlspecialchars($book['book_title']); ?></div>
              </div>
            </a>
          </div>
  <?php }
    } else {
        echo "<div class='text-center mt-4'>No books found.</div>";
    }
  ?>
</div>

<?php
  if (isset($conn)) {mysqli_close($conn);}
  require_once "./template/footer.php";
?>
