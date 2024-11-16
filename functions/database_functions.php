<?php
// Function to connect to the database
function db_connect() {
    $conn = mysqli_connect("db", "root", "rootpassword", "obs_db");
    if(!$conn){
        echo "Can't connect to the database: " . mysqli_connect_error();
        exit;
    }
    return $conn;
}

// Function to fetch the latest 4 books
function select4LatestBook($conn) {
    $row = array();
    $query = "SELECT book_isbn, book_title FROM books ORDER BY ABS(UNIX_TIMESTAMP(created_at)) DESC"; // Removed book_image
    $result = mysqli_query($conn, $query);
    if(!$result) {
        echo "Can't retrieve data " . mysqli_error($conn);
        exit;
    }
    for($i = 0; $i < 4; $i++) {
        array_push($row, mysqli_fetch_assoc($result));
    }
    return $row;
}

// Function to fetch book details by ISBN
function getBookByIsbn($conn, $isbn) {
    $query = "SELECT book_title, book_author, book_price, book_descr, publisherid FROM books WHERE book_isbn = ?"; // Removed book_image
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $isbn);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Function to get order ID by customer ID
function getOrderId($conn, $customerid) {
    $query = "SELECT orderid FROM orders WHERE customerid = ?";
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "i", $customerid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['orderid'];
}

// Function to insert a new order (without shipping data)
function insertIntoOrder($conn, $customerid, $total_price, $date) { // Removed shipping fields
    $query = "INSERT INTO orders (customerid, total_price, date)
              VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "ids", $customerid, $total_price, $date);
    $result = mysqli_stmt_execute($stmt);
    if(!$result) {
        echo "Insert order failed: " . mysqli_error($conn);
        exit;
    }
}

// Function to get book price by ISBN
function getBookPrice($isbn) {
    $conn = db_connect();
    $query = "SELECT book_price FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $isbn);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['book_price'];
}

// Function to get customer ID by name, address, and contact
function getCustomerId($name, $address, $contact) {
    $conn = db_connect();
    $query = "SELECT customerid FROM customers WHERE `name` = ? AND `address` = ? AND contact = ?"; // Removed city, zip_code, country
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "sss", $name, $address, $contact);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['customerid'];
    } else {
        return null;
    }
}

// Function to insert a new customer and return customer ID
function setCustomerId($name, $address, $contact) { // Removed city, zip_code, country
    $conn = db_connect();
    $query = "INSERT INTO customers (name, address, contact) 
              VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "sss", $name, $address, $contact);
    $result = mysqli_stmt_execute($stmt);
    if(!$result) {
        echo "Insert failed: " . mysqli_error($conn);
        exit;
    }
    $customerid = mysqli_insert_id($conn);
    return $customerid;
}

// Function to get publisher name by publisher ID
function getPubName($conn, $pubid) {
    $query = "SELECT publisher_name FROM publisher WHERE publisherid = ?";
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "i", $pubid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['publisher_name'];
}

// Function to fetch all books from the database
function getAll($conn) {
    $query = "SELECT * FROM books ORDER BY book_isbn DESC";
    $result = mysqli_query($conn, $query);
    if(!$result) {
        echo "Can't retrieve data: " . mysqli_error($conn);
        exit;
    }
    return $result;
}

// Function to add a new book to the database
function addBook($conn, $isbn, $title, $author, $description, $price, $publisherid) { // Removed image
    $query = "INSERT INTO books (book_isbn, book_title, book_author, book_descr, book_price, publisherid)
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "ssssdi", $isbn, $title, $author, $description, $price, $publisherid);
    $result = mysqli_stmt_execute($stmt);
    if(!$result) {
        echo "Insert book failed: " . mysqli_error($conn);
        exit;
    }
}

// Function to update book details in the database
function updateBook($conn, $isbn, $title, $author, $description, $price, $publisherid) { // Removed image
    $query = "UPDATE books SET book_title = ?, book_author = ?, book_descr = ?, book_price = ?, publisherid = ? WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "ssssdis", $title, $author, $description, $price, $publisherid, $isbn);
    $result = mysqli_stmt_execute($stmt);
    if(!$result) {
        echo "Update book failed: " . mysqli_error($conn);
        exit;
    }
}

// Function to delete a book from the database
function deleteBook($conn, $isbn) {
    $query = "DELETE FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if(!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $isbn);
    $result = mysqli_stmt_execute($stmt);
    if(!$result) {
        echo "Delete book failed: " . mysqli_error($conn);
        exit;
    }
}
?>
