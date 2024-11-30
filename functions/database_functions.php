<?php
// Database connection function
function db_connect() {
    $conn = mysqli_connect("db", "root", "rootpassword", "obs_db");
    if (!$conn) {
        error_log("Can't connect to the database: " . mysqli_connect_error(), 3, "/var/www/html/logs/error_log.log");
        exit("Database connection failed. Please try again later.");
    }
    return $conn;
}

// Fetch the latest 4 books
function select4LatestBook($conn) {
    $row = array();
    $query = "SELECT book_isbn, book_image, book_title FROM books ORDER BY abs(unix_timestamp(created_at)) DESC LIMIT 4";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error preparing the SQL statement.");
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error fetching the latest books.");
    }

    while ($book = mysqli_fetch_assoc($result)) {
        $row[] = $book;
    }
    mysqli_stmt_close($stmt);
    return $row;
}

// Get book by ISBN
function getBookByIsbn($conn, $isbn) {
    $query = "SELECT book_title, book_author, book_price, book_descr, book_image FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error preparing the SQL statement.");
    }

    mysqli_stmt_bind_param($stmt, "s", $isbn); // Bind ISBN parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error fetching book details.");
    }
    $book = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $book;
}

// Insert a new order into the orders table
function insertIntoOrders ($conn, $customerid, $total_price, $order_date, $name, $address, $contact, $payment_method) {
    if (!$order_date) {
        $order_date = date('Y-m-d');
    }

    $query = "INSERT INTO orders (customerid, total_price, order_date, 'name', 'address', 'contact', payment_method)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error preparing the SQL statement: " . mysqli_error($conn));
    }
    else{
        echo"statement prepared successfully.";
    }

    mysqli_stmt_bind_param($stmt, "idsssss", $customerid, $total_price, $order_date, $name, $address, $contact, $payment_method);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        error_log("Insert order failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Failed to insert order.");
    }

    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    return $order_id;
}

// Insert items into the order_items table
function insertOrderItems($order_id, $isbn, $book_price, $quantity) {
    $conn = db_connect();

    $query = "INSERT INTO order_items (order_id, isbn, price, quantity) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error preparing the SQL statement.");
    }

    mysqli_stmt_bind_param($stmt, "isdi", $order_id, $isbn, $book_price, $quantity);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        error_log("Insert order item failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Failed to insert order item.");
    }

    mysqli_stmt_close($stmt);
    return $result;
}

// Get or insert customer and return customer ID
function getOrInsertCustomerId($name, $address, $contact) {
    $conn = db_connect();
    $query = "SELECT customerid FROM customers WHERE 'name'= ? AND 'address' = ? AND contact = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error preparing the SQL statement.");
    }

    mysqli_stmt_bind_param($stmt, "sss", $name, $address, $contact);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error retrieving customer ID.");
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row['customerid'];
    } else {
        $insertQuery = "INSERT INTO customers (customer_id,'name', 'address', contact) VALUES (?,?, ?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertQuery);
        if (!$insertStmt) {
            error_log("Insert prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
            exit("Failed to prepare insert statement.");
        }

        mysqli_stmt_bind_param($insertStmt, "sss", $name, $address, $contact);
        if (mysqli_stmt_execute($insertStmt)) {
            $customerId = mysqli_insert_id($conn);
            mysqli_stmt_close($insertStmt);
            return $customerId;
        } else {
            error_log("Insert customer failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
            exit("Failed to insert customer.");
        }
    }
}

// Get book price by ISBN
function getbookprice($isbn) {
    $conn = db_connect();
    $query = "SELECT book_price FROM books WHERE book_isbn = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error preparing the SQL statement.");
    }

    mysqli_stmt_bind_param($stmt, "s", $isbn);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error fetching book price.");
    }
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row['book_price'];
}

// Get publisher name by publisherid
function getPublisherName($conn, $publisherid) {
    $query = "SELECT publisher_name FROM publisher WHERE publisherid = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error preparing the SQL statement.");
    }

    mysqli_stmt_bind_param($stmt, "i", $publisherid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error fetching publisher name.");
    }

    if (mysqli_num_rows($result) == 0) {
        return "Unknown Publisher";
    }
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row['publisher_name'];
}

// Get all books from the database
function getAll($conn) {
    $query = "SELECT * FROM books ORDER BY book_isbn DESC";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error preparing the SQL statement.");
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log("Query failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error fetching all books.");
    }

    $books = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $books;
}
function selectTopSellingBooks($conn, $limit = 4) {
    $query = "SELECT * FROM books ORDER BY sales_count DESC LIMIT ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error fetching top-selling books: " . mysqli_error($conn));
    }

    $books = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
    return $books;
}
function getBooksAndPublishers($conn) {
    $sql = "SELECT books.*, publisher.publisher_name 
            FROM books 
            INNER JOIN publisher
            ON books.publisher_id = publisher.publisher_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error fetching books and publisher: " . mysqli_error($conn));
    }

    return $result;
}


?>