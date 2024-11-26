<?php
// include_once 'helper_function.php';
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

// Get or insert customer and return customer ID
function getOrInsertCustomerId($name, $address, $contact) {
    $conn = db_connect();
    $query = "SELECT customerid FROM customers WHERE name = ? AND address = ? AND contact = ?";
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
        $insertQuery = "INSERT INTO customers (name, address, contact) VALUES (?, ?, ?)";
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

// Insert a new order into the orders table
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
<<<<<<< Updated upstream


function getBooksAndPublishers($conn) {
    $query = "
        SELECT 
            books.book_isbn, 
            books.book_title, 
            books.book_author, 
            books.book_price, 
            books.book_descr, 
            books.book_image, 
            publisher.publisher_name  
        FROM 
            books 
        JOIN 
            publisher 
        ON 
            books.publisherid = publisher.publisherid 
        ORDER BY 
            books.book_isbn DESC
    ";


    $result = mysqli_query($conn, $query);
    // debug($query);
    // debug($result, 1);


    if (!$result) {
        error_log("Can't retrieve books and publishers: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
        exit("Failed to retrieve books and publishers.");
    }

    return $result;
    // $books = [];
    // while ($row = mysqli_fetch_assoc($result)) {
    //     $books[] = $row; // Fetch all books with publisher names into an array
    // }
    // return $books; // Return the array of books with publisher names
}
function getOrderList($conn)
{
    $query = "SELECT
            orders.orderid,
            orders.payment_method,
            orders.status,
            orders.remarks,
            orders.total_price,
            customers.name AS customer_name,
            customers.address AS customer_address,
            customers.contact AS customer_contact
        FROM 
            orders
        JOIN 
            customers
        ON 
            customers.customerid = orders.customerid
        ORDER BY 
            orders.order_date DESC;";

    $result = mysqli_query($conn, $query);

    // if (!$result) {
    //     error_log("Can't retrieve books and publishers: " . mysqli_error($conn), 3, "/var/www/html/logs/error_log.log");
    //     exit("Failed to retrieve books and publishers.");
    // }

    // return $result;
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row; // Fetch all books with publisher names into an array
    }
    return $orders; // Return the array of books with publisher names
}

function getOrderById($conn, $orderId)
{
    $query = "SELECT
                orders.orderid,
                orders.payment_method,
                orders.status,
                orders.remarks,
                orders.total_price,
                orders.customerid AS customer_id,
                customers.name AS customer_name,
                customers.address AS customer_address,
                customers.contact AS customer_contact
            FROM 
                orders
            JOIN 
                customers
            ON 
                customers.customerid = orders.customerid
            WHERE
                orders.orderid = $orderId
            ORDER BY 
                orders.order_date DESC;";

// debug($query);


$result = mysqli_query($conn, $query);


$order = mysqli_fetch_assoc($result);

return $order;

}

function getAdminOrderBookList($conn, $orderId, $customer_id)
{

    $query = " SELECT 
                order_items.book_isbn,
                order_items.quantity,
                books.book_title,
                books.book_author,
                books.book_price,
                publisher.publisher_name
            FROM 
                orders
            JOIN 
                order_items ON orders.orderid = order_items.order_id
            JOIN 
                books ON order_items.book_isbn = books.book_isbn
            JOIN 
                publisher ON books.publisherid = publisher.publisherid
            WHERE 
                orders.orderid = '{$orderId}' 
                AND orders.customerid = '{$customer_id}'";

// debug($query, 1);
    $result = mysqli_query($conn, $query);

    $orderBooks = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orderBooks[] = $row; // Fetch all books with publisher names into an array
    }
    return $orderBooks; // Return the array of books with publisher names
=======
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
>>>>>>> Stashed changes
}

?>
