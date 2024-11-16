<?php
// Function to connect to the database
function db_connect() {
    $conn = mysqli_connect("db", "root", "rootpassword", "obs_db");
    if (!$conn) {
        // Log error to the correct path
        error_log("Database connection failed: " . mysqli_connect_error(), 3, "/var/www/html/logs/error_log.log");
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
        // Log error if query fails
        error_log("Error fetching all books: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        die("Error fetching the books.");
    }
    return $result;
}

// Function to fetch publisher name by ID (1, 2, or 3)
function getPubName($conn, $publisherid) {
    // Use a simple switch-case since there are only three publishers
    switch ($publisherid) {
        case 1:
            return "Publisher 1"; // Replace with the actual name if needed
        case 2:
            return "Publisher 2"; // Replace with the actual name if needed
        case 3:
            return "Publisher 3"; // Replace with the actual name if needed
        default:
            return "Unknown Publisher"; // Fallback in case of invalid publisher ID
    }
}

// Function to fetch book details by ISBN (with image)
function getBookByIsbn($conn, $isbn) {
    $query = "SELECT book_title, book_author, book_price, book_descr, book_image, publisherid FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        // Log error if statement preparation fails
        error_log("Error preparing statement: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
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
        // Log error if statement preparation fails
        error_log("Error preparing statement: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        die("Failed to prepare statement.");
    }
    mysqli_stmt_bind_param($stmt, "ssssdis", $isbn, $title, $author, $description, $price, $publisherid, $image);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        // Log error if insertion fails
        error_log("Error inserting book: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        die("Failed to add book.");
    }
    mysqli_stmt_close($stmt);
}

// Function to update book details (with image)
function updateBook($conn, $isbn, $title, $author, $description, $price, $publisherid, $image) {
    $query = "UPDATE books SET book_title = ?, book_author = ?, book_descr = ?, book_price = ?, publisherid = ?, book_image = ? WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        // Log error if statement preparation fails
        error_log("Error preparing statement: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        die("Failed to prepare update statement.");
    }
    mysqli_stmt_bind_param($stmt, "ssssdis", $title, $author, $description, $price, $publisherid, $image, $isbn);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        // Log error if update fails
        error_log("Error updating book: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        die("Failed to update book.");
    }
    mysqli_stmt_close($stmt);
}

// Function to delete a book from the database
function deleteBook($conn, $isbn) {
    $query = "DELETE FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        // Log error if statement preparation fails
        error_log("Error preparing statement: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        die("Failed to prepare delete statement.");
    }
    mysqli_stmt_bind_param($stmt, "s", $isbn);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        // Log error if deletion fails
        error_log("Error deleting book: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        die("Failed to delete book.");
    }
    mysqli_stmt_close($stmt);
}

// Function to fetch the latest 4 books
function select4LatestBook($conn) {
    // Define the SQL query to fetch the latest 4 books
    $sql = "SELECT book_isbn, book_title, book_image FROM books ORDER BY book_date_added DESC LIMIT 4";
    
    // Execute the query
    $result = mysqli_query($conn, $sql);
    
    // Check for errors in the query execution
    if (!$result) {
        // Log error if query fails
        error_log("Error fetching latest books: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        die("Error fetching the latest books.");
    }

    // Fetch the results as an associative array
    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // Return the fetched books
    return $books;
}
?>
