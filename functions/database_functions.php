<?php
// Existing database connection function
function db_connect() {
    $conn = mysqli_connect("db", "root", "rootpassword", "obs_db");
    if (!$conn) {
        // Log error message instead of direct echo
        error_log("Can't connect database: " . mysqli_connect_error(), 3, "/var/www/html/logs/error_log.log");
        exit("Database connection failed.");
    }
    return $conn;
}

// Existing functions (no change in these)
function select4LatestBook($conn) {
    $row = array();
    $query = "SELECT book_isbn, book_image, book_title FROM books ORDER BY abs(unix_timestamp(created_at)) DESC LIMIT 4";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Can't retrieve data: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error fetching the latest books.");
    }
    while ($book = mysqli_fetch_assoc($result)) {
        $row[] = $book; // Fetch and add books to the array
    }
    return $row;
}

function getBookByIsbn($conn, $isbn) {
    $query = "SELECT book_title, book_author, book_price, book_descr, book_image FROM books WHERE book_isbn = '$isbn'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Can't retrieve data: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error fetching book details.");
    }
    return mysqli_fetch_assoc($result); // Fetch a single row as associative array
}

// Update insertIntoOrder to take customer_id
function insertIntoOrder($conn, $customerid, $total_price, $order_date, $name, $address, $contact, $payment_method) {
    // Ensure order_date is in correct format
    if (!$order_date) {
        $order_date = date('Y-m-d'); // Default to current date if not provided
    }

    // Prepare the SQL statement to prevent SQL injection
    $query = "INSERT INTO orders (customerid, total_price, order_date, name, address, contact, payment_method) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameters (Ensure data types match)
    mysqli_stmt_bind_param($stmt, "idsssss", $customerid, $total_price, $order_date, $name, $address, $contact, $payment_method);

    // Execute the statement
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Insert orders failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Failed to insert order.");
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}


// Function to insert order items
// Function to insert order items into the database
function insertOrderItem($order_id, $isbn, $book_price, $quantity) {
    global $conn;  // Ensure the database connection is accessible

    $query = "INSERT INTO order_items (order_id, isbn, price, quantity) 
              VALUES ('$order_id', '$isbn', '$book_price', '$quantity')";
    
    if (mysqli_query($conn, $query)) {
        return true;  // Return true if the query is successful
    } else {
        return false;  // Return false if there is an error
    }
}


// Function to get the customer ID or insert a new customer if not found
function getOrInsertCustomerId($name, $address, $contact) {
    $conn = db_connect();
    // First, try to get the customer ID
    $query = "SELECT customerid FROM customers WHERE name = '$name' AND address = '$address' AND contact = '$contact'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error retrieving customer ID: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Error retrieving customer ID.");
    }
    
    if (mysqli_num_rows($result) > 0) {
        // Customer exists, return the customer ID
        $row = mysqli_fetch_assoc($result);
        return $row['customerid'];
    } else {
        // Customer doesn't exist, insert a new customer
        $query = "INSERT INTO customers (name, address, contact) VALUES ('$name', '$address', '$contact')";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            error_log("Insert customer failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
            exit("Failed to insert customer.");
        }
        return mysqli_insert_id($conn); // Return the new customer ID
    }
}

// Other existing functions (no changes needed)
function getbookprice($isbn) {
    $conn = db_connect();
    $query = "SELECT book_price FROM books WHERE book_isbn = '$isbn'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Get book price failed: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Failed to retrieve book price.");
    }
    $row = mysqli_fetch_assoc($result);
    return $row['book_price'];
}

function getPublisherName($conn, $publisherid) {
    $query = "SELECT publisher_name FROM publishers WHERE publisherid = '$publisherid'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Can't retrieve publisher name: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Failed to retrieve publisher name.");
    }
    if (mysqli_num_rows($result) == 0) {
        return "Unknown Publisher"; // If no publisher found, return a default name
    }
    $row = mysqli_fetch_assoc($result);
    return $row['publisher_name'];
}

function getAll($conn) {
    $query = "SELECT * FROM books ORDER BY book_isbn DESC";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Can't retrieve all books: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Failed to retrieve all books.");
    }

    $books = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row; // Fetch all books into an array
    }
    return $books; // Return the array of books
}
?>
