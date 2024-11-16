<?php
// Function to connect to the database
function db_connect() {
    $conn = mysqli_connect("db", "root", "rootpassword", "obs_db");
    if (!$conn) {
        echo "Can't connect to the database: " . mysqli_connect_error();
        exit;
    }
    return $conn;
}

// Function to fetch the latest 4 books (with images)
function select4LatestBook($conn) {
    $row = array();
    $query = "SELECT book_isbn, book_title, book_image FROM books ORDER BY ABS(UNIX_TIMESTAMP(created_at)) DESC LIMIT 4";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Can't retrieve data " . mysqli_error($conn);
        exit;
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
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $isbn);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Function to fetch all books (with images)
function getAll($conn) {
    $query = "SELECT book_isbn, book_title, book_image FROM books ORDER BY book_isbn DESC";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Can't retrieve data: " . mysqli_error($conn);
        exit;
    }
    return $result;
}

// Function to add a new book (with image)
function addBook($conn, $isbn, $title, $author, $description, $price, $publisherid, $image) {
    $query = "INSERT INTO books (book_isbn, book_title, book_author, book_descr, book_price, publisherid, book_image)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "ssssdis", $isbn, $title, $author, $description, $price, $publisherid, $image);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        echo "Insert book failed: " . mysqli_error($conn);
        exit;
    }
}

// Function to update book details (with image)
function updateBook($conn, $isbn, $title, $author, $description, $price, $publisherid, $image) {
    $query = "UPDATE books SET book_title = ?, book_author = ?, book_descr = ?, book_price = ?, publisherid = ?, book_image = ? WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "ssssdis", $title, $author, $description, $price, $publisherid, $image, $isbn);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        echo "Update book failed: " . mysqli_error($conn);
        exit;
    }
}

// Function to delete a book from the database
function deleteBook($conn, $isbn) {
    $query = "DELETE FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $isbn);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        echo "Delete book failed: " . mysqli_error($conn);
        exit;
    }
}
?>
