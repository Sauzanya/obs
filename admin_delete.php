<?php
    // Start session and include necessary files
    session_start();
    require_once "./functions/database_functions.php";
    $conn = db_connect();

    // Check if book ISBN is provided
    if (isset($_GET['bookisbn'])) {
        $book_isbn = $_GET['bookisbn'];
    } else {
        echo "Invalid request. No ISBN provided.";
        exit;
    }

    // Confirm deletion before proceeding
    if (isset($_POST['confirm_delete'])) {
        // Prepare and execute delete query using a prepared statement to prevent SQL injection
        $query = "DELETE FROM books WHERE book_isbn = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $book_isbn);

        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Redirect to admin book page with success message
            $_SESSION['book_success'] = "Book has been successfully deleted.";
            header("Location: admin_book.php");
            exit;
        } else {
            // Show error message if deletion failed
            echo "Delete operation unsuccessful. " . mysqli_error($conn);
            exit;
        }
    }

    // Fetch book details to show information about the book being deleted
    $query = "SELECT book_title FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $book_isbn);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $book_title);
    mysqli_stmt_fetch($stmt);

    // If no book found, show an error
    if (empty($book_title)) {
        echo "No book found with this ISBN.";
        exit;
    }

    mysqli_stmt_close($stmt);
?>

<?php
// Start the session and include any necessary session or PHP logic at the beginning
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $title; ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./bootstrap/css/styles.css" rel="stylesheet">
    <link href="./bootstrap/custom_css/custom.css" rel="stylesheet">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="./bootstrap/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="./bootstrap/js/bootstrap.bundle.min.js"></script>
  </head>

  <body>
    <div class="clear-fix pt-5 pb-3"></div>
    <nav class="navbar navbar-expand-lg navbar-expand-md navbar-light bg-warning bg-gradient fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav" aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <a class="navbar-brand" href="index.php">Introducing Online Book Shop</a>
        </div>
        <div class="collapse navbar-collapse" id="topNav">
          <ul class="nav navbar-nav">
            <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == true): ?>
                <li class="nav-item"><a class="nav-link" href="admin_book.php"><span class="fa fa-th-list"></span> Book List</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_add.php"><span class="far fa-plus-square"></span> Add New Book</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_orderlist.php"><span class="far fa-plus-square"></span> Order List</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_signout.php"><span class="fa fa-sign-out-alt"></span> Logout</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="publisher_list.php"><span class="fa fa-paperclip"></span> Publisher</a></li>
              <li class="nav-item"><a class="nav-link" href="books.php"><span class="fa fa-book"></span> Books</a></li>
              <li class="nav-item"><a class="nav-link" href="cart.php"><span class="fa fa-shopping-cart"></span> My Cart</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Optional: Show welcome message on the home page -->
    <?php if(isset($title) && $title == "Home"): ?>
      <div class="container">
        <h1>Welcome to Emerging World of Online Book Shop</h1>
        <hr>
      </div>
    <?php endif; ?>

    <div class="container" id="main">







    <h3>Are you sure you want to delete the book: "<?php echo htmlspecialchars($book_title); ?>"?</h3>
    <form method="post" action="admin_delete.php?bookisbn=<?php echo $book_isbn; ?>">
        <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
        <a href="admin_book.php" class="btn btn-secondary">Cancel</a>
    </form>



    <hr>

<footer class="fixed-bottom bg-light bg-gradient border py-3 px-2">
  <div class="container">
      <div class="d-flex justify-content-between">
          <div class="">
              <a href="#" target="_blank" class="text-decoration-none text-muted fw-bold"> Simple Online Book Stores Site &copy; <?= date('Y') ?> </a>
          </div>
          <div class="">
              <?php if(!isset($_SESSION['admin'])){ ?>
                  <a href="admin.php" class="text-decoration-none text-dark fw-bolder">Login as Admin</a>
              <?php } ?>
          </div>
      </div>
  </div>
</footer>
<div class="clear-fix py-4"></div>
</div> <!-- /container -->

</body>
</html>

<?php
    // Close database connection
    mysqli_close($conn);
?>
