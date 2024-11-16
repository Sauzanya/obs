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

// Function to fetch all books with detailed information
function getAll($conn) {
    // Include all the necessary columns for display
    $query = "SELECT book_isbn, book_title, book_author, book_descr, book_image, book_price, publisherid 
              FROM books ORDER BY book_isbn DESC";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error fetching all books: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        die("Error fetching the books.");
    }
    return $result;
}

// Function to fetch publisher name by ID (using prepared statements for security)
function getPubName($conn, $publisherid) {
    // Prepare the query to prevent SQL injection
    $query = "SELECT publisher_name FROM publishers WHERE publisher_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        error_log("Error preparing publisher query: " . mysqli_error($conn), 3, "/path/to/logfile.log"); // Log error
        return "Unknown Publisher"; // Return fallback value
    }
    
    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "i", $publisherid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        mysqli_stmt_close($stmt);
        return $row['publisher_name'];
    }
    
    mysqli_stmt_close($stmt);
    return "Unknown Publisher"; // Fallback if no publisher is found
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
