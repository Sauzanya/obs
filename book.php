<?php
  session_start();
  $book_isbn = $_GET['bookisbn'];
  
  // Connect to the database
  require_once "./functions/database_functions.php";
  $conn = db_connect();

  // Fetch the details of the current book
  $query = "SELECT * FROM books WHERE book_isbn = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $book_isbn);
  $stmt->execute();
  $result = $stmt->get_result();

  if(!$result || $result->num_rows === 0){
    echo "Book not found.";
    exit;
  }

  $row = $result->fetch_assoc();
  $title = $row['book_title'];
  $author = $row['book_author'];

  // Store the current book ISBN in the session (track user activity)
  $_SESSION['last_viewed_book_isbn'] = $book_isbn;

  require "./template/header.php";
?>
      <!-- Book details section -->
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="books.php" class="text-decoration-none text-muted fw-light">Publishers</a></li>
          <li class="breadcrumb-item active" aria-current="page"><?php echo $row['book_title']; ?></li>
        </ol>
      </nav>
      <div class="row">
        <div class="col-md-3 text-center book-item">
          <div class="img-holder overflow-hidden">
            <img class="img-top" src="./bootstrap/img/<?php echo $row['book_image']; ?>">
          </div>
        </div>
        <div class="col-md-9">
          <div class="card rounded-0 shadow">
            <div class="card-body">
              <div class="container-fluid">
                <h4><?= $row['book_title'] ?></h4>
                <hr>
                <p><?php echo $row['book_descr']; ?></p>
                <h4>Details</h4>
                <table class="table">
                  <?php foreach($row as $key => $value){
                    if($key == "book_descr" || $key == "book_image" || $key == "publisherid" || $key == "book_title"){
                      continue;
                    }
                    switch($key){
                      case "book_isbn":
                        $key = "ISBN";
                        break;
                      case "book_title":
                        $key = "Title";
                        break;
                      case "book_author":
                        $key = "Author";
                        break;
                      case "book_price":
                        $key = "Price";
                        break;
                    }
                  ?>
                  <tr>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $value; ?></td>
                  </tr>
                  <?php } ?>
                </table>
                <form method="post" action="cart.php">
                  <input type="hidden" name="bookisbn" value="<?php echo $book_isbn; ?>">
                  <div class="text-center">
                    <input type="submit" value="Purchase / Add to cart" name="cart" class="btn btn-primary rounded-0">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recommended Books Section -->
      <div class="container mt-5">
        <h4>Recommended Books:</h4>
        <ul>
        <?php
          // Check if there's a last viewed book in the session
          if (isset($_SESSION['last_viewed_book_isbn'])) {
              $lastBookIsbn = $_SESSION['last_viewed_book_isbn'];
              // Fetch the author of the last viewed book
              $recommend_query = "SELECT book_author FROM books WHERE book_isbn = ?";
              $recommend_stmt = $conn->prepare($recommend_query);
              $recommend_stmt->bind_param("s", $lastBookIsbn);
              $recommend_stmt->execute();
              $recommend_result = $recommend_stmt->get_result();

              if ($recommend_result->num_rows > 0) {
                  $lastViewedBook = $recommend_result->fetch_assoc();
                  $lastBookAuthor = $lastViewedBook['book_author'];

                  // Fetch books by the same author excluding the current one
                  $recommend_query = "SELECT * FROM books WHERE book_author = ? AND book_isbn != ? LIMIT 10";
                  $recommend_stmt = $conn->prepare($recommend_query);
                  $recommend_stmt->bind_param("ss", $lastBookAuthor, $book_isbn);
                  $recommend_stmt->execute();
                  $recommend_result = $recommend_stmt->get_result();

                  if ($recommend_result->num_rows > 0) {
                      while ($rec_book = $recommend_result->fetch_assoc()) {
                          echo "<li><a href='book.php?bookisbn=" . $rec_book['book_isbn'] . "'>" . $rec_book['book_title'] . " by " . $rec_book['book_author'] . "</a></li>";
                      }
                  } else {
                      echo "<p>No recommendations available.</p>";
                  }
              }
          } else {
              echo "<p>No previous activity to base recommendations on.</p>";
          }
        ?>
        </ul>
      </div>

<?php
  // Close the database connection
  $conn->close();
  require "./template/footer.php";
?>
