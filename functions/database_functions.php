<?php
// Database connection function
function db_connect() {
    // $conn = mysqli_connect("db", "root", "rootpassword", "obs_db");
    $conn = mysqli_connect("localhost", "root", 'P@$$w0rd', "obs_db");
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

function insertIntoOrder($conn, $customerid, $total_price, $order_date, $payment_method) {
  
    if (!$order_date) {
        $order_date = date('Y-m-d');
    }
     // Prepare the SQL statement
     $query = "INSERT INTO orders (customerid, total_price, order_date, payment_method) VALUES (?, ?, ?, ?)";
     $stmt = mysqli_prepare($conn, $query);
     if (!$stmt) {
         error_log("Prepare failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
         exit("Error preparing the SQL statement.");
     }
 
     // Bind parameters: customerid (integer), total_price (double), order_date (string), payment_method (string)
     mysqli_stmt_bind_param($stmt, "idss", $customerid, $total_price, $order_date, $payment_method);
 
     // Execute the statement
     $result = mysqli_stmt_execute($stmt);
     if (!$result) {
         error_log("Insert order failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
         exit("Failed to insert order. Check your inputs or database.");
     }
 
     // Retrieve the inserted order ID
     $order_id = mysqli_insert_id($conn);
 
     // Close the statement
     mysqli_stmt_close($stmt);
 
    return $order_id;
}


// Insert items into the order_items table
function insertOrderItem($order_id, $isbn, $book_price, $quantity) {
    $conn = db_connect();
    $query = "INSERT INTO order_items (order_id, book_isbn, book_price, quantity) VALUES (?, ?, ?, ?)";
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

// Insert items into the order_items table
function insertOrderItems($order_id, $isbn, $book_price, $quantity) {
    
    $conn = db_connect();

    $query = "INSERT INTO order_items (order_id, book_isbn, book_price, quantity) VALUES (?, ?, ?, ?)";
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

function getOrInsertCustomerId($name, $address, $contact) {
    $conn = db_connect();

    // Check if the customer already exists
    $query = "SELECT customerid FROM customers WHERE name = ? AND address = ? AND contact = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Error preparing statement: " . mysqli_error($conn));
        exit("Failed to prepare SQL statement.");
    }

    // Bind parameters and execute the query
    mysqli_stmt_bind_param($stmt, "sss", $name, $address, $contact);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log("Error executing statement: " . mysqli_error($conn));
        exit("Error retrieving customer ID.");
    }

    // If the customer exists, return the customer ID
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row['customerid'];
    }

    // If the customer does not exist, insert a new record
    $insertQuery = "INSERT INTO customers (name, address, contact) VALUES (?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertQuery);
    if (!$insertStmt) {
        error_log("Error preparing insert statement: " . mysqli_error($conn));
        exit("Failed to prepareeeeeeeeeeeeeeeeeee insert statement.");
    }

    // Bind parameters and execute the insert query
    mysqli_stmt_bind_param($insertStmt, "sss", $name, $address, $contact);
    if (mysqli_stmt_execute($insertStmt)) {
        $customerId = mysqli_insert_id($conn); // Get the newly inserted customer ID
        mysqli_stmt_close($insertStmt);
        return $customerId;
    } else {
        error_log("Error inserting customer: " . mysqli_error($conn));
        exit("Failed to insert customer.");
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
function getBooksAndPublisher($conn) {
    $sql = "SELECT books.*, publisher.publisher_name 
            FROM books 
            INNER JOIN publisher
            ON books.publisherid = publisher.publisherid";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error fetching books and publisher: " . mysqli_error($conn));
    }

    return $result;
}
// In functions/admin.php or functions/database_functions.php
function getOrderList($conn) {
    $query = "SELECT * FROM orders"; // Adjust the table name and columns as needed
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    return $orders;
}

// In functions/admin.php or functions/database_functions.php
function getOrderById($conn, $order_id) {
    $order_id = mysqli_real_escape_string($conn, $order_id);
    $query = "SELECT * FROM orders WHERE orderid = '{$order_id}'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    return mysqli_fetch_assoc($result);
}

function getAdminOrderBookList($conn, $order_id) {
    // Define the query with placeholders
    $query = "
        SELECT 
            oi.book_isbn,
            oi.quantity,
            b.book_isbn,
            b.book_title,
            b.book_author,
            b.book_price,
            p.publisher_name
        FROM 
            order_items oi
        JOIN 
            books b ON oi.book_isbn = b.book_isbn
        JOIN 
            publisher p ON b.publisherid = p.publisherid
        WHERE 
            oi.order_id = ?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Error preparing statement: " . mysqli_error($conn));
        exit("Error preparing the SQL statement.");
    }

    // Bind the parameter (assuming order_id is an integer)
    mysqli_stmt_bind_param($stmt, "i", $order_id);

    // Execute the query
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Error executing statement: " . mysqli_error($conn));
        exit("Error executing the SQL statement.");
    }

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        error_log("Error retrieving results: " . mysqli_error($conn));
        exit("Error retrieving order book list.");
    }

    // Fetch results as an associative array
    $bookList = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bookList[] = $row;
    }

    // Clean up
    mysqli_stmt_close($stmt);

    return $bookList;
}


?>