<?php
// Function to connect to the database
function db_connect() {
    $conn = mysqli_connect("db", "root", "rootpassword", "obs_db");
    if (!$conn) {
        error_log("Database connection failed: " . mysqli_connect_error(), 3, "/path/to/logfile.log"); // Log error to file
        die("Database connection failed. Please try again later.");
    }
    return $conn;
}

// Function to fetch the latest 4 books (with images)
function select4LatestBook($conn) {
    $row = array();
    $query = "SELECT book_isbn, book_title, book_image FROM books ORDER BY created_at DESC LIMIT 4"; 
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error fetching latest books: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Error fetching the latest books.");
    }
    while ($book = mysqli_fetch_assoc($result)) {
        $row[] = $book;
    }
    return $row;
}

// Function to fetch book details by ISBN (with image)
function getBookByIsbn($conn, $isbn) {
    $query = "SELECT book_title, book_author, book_price, book_descr, book_image, publisherid FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Error preparing statement: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Error fetching book details.");
    }
    mysqli_stmt_bind_param($stmt, "s", $isbn);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $book ? $book : null; // Return null if no book is found
}

// Function to fetch all books (with images)
function getAll($conn) {
    $query = "SELECT book_isbn, book_title, book_image FROM books ORDER BY book_isbn DESC";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error fetching all books: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Error fetching the books.");
    }
    return $result;
}

// Function to add a new book (with image)
function addBook($conn, $isbn, $title, $author, $description, $price, $publisherid, $image) {
    $query = "INSERT INTO books (book_isbn, book_title, book_author, book_descr, book_price, publisherid, book_image)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Error preparing statement: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Failed to prepare statement.");
    }
    mysqli_stmt_bind_param($stmt, "ssssdis", $isbn, $title, $author, $description, $price, $publisherid, $image);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        error_log("Error inserting book: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Failed to add book.");
    }
    mysqli_stmt_close($stmt);
}

// Function to update book details (with image)
function updateBook($conn, $isbn, $title, $author, $description, $price, $publisherid, $image) {
    $query = "UPDATE books SET book_title = ?, book_author = ?, book_descr = ?, book_price = ?, publisherid = ?, book_image = ? WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Error preparing statement: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Failed to prepare update statement.");
    }
    mysqli_stmt_bind_param($stmt, "ssssdis", $title, $author, $description, $price, $publisherid, $image, $isbn);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        error_log("Error updating book: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Failed to update book.");
    }
    mysqli_stmt_close($stmt);
}

// Function to delete a book from the database
function deleteBook($conn, $isbn) {
    $query = "DELETE FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Error preparing statement: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Failed to prepare delete statement.");
    }
    mysqli_stmt_bind_param($stmt, "s", $isbn);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        error_log("Error deleting book: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Failed to delete book.");
    }
    mysqli_stmt_close($stmt);
}
?>