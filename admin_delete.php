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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Book</title>
</head>
<body>
    <h3>Are you sure you want to delete the book: "<?php echo htmlspecialchars($book_title); ?>"?</h3>
    <form method="post" action="admin_delete.php?bookisbn=<?php echo $book_isbn; ?>">
        <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
        <a href="admin_book.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>

<?php
    // Close database connection
    mysqli_close($conn);
?>
