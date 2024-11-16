<?php
// Function to connect to the database
function db_connect(){
    // Connect to MySQL server
    $conn = mysqli_connect("db", "root", "rootpassword", "obs_db");

    if (!$conn) {
        echo "Can't connect database: " . mysqli_connect_error(); // Show connection error
        exit;  // Exit if unable to connect
    }
    return $conn;
}

// Function to get all books from the database
function getAll($conn){
    // Prepare SQL query to fetch all books
    $query = "SELECT * FROM books ORDER BY book_isbn DESC";
    $result = mysqli_query($conn, $query);  // Execute query

    if (!$result) {
        // Show error if query fails
        echo "Can't retrieve data: " . mysqli_error($conn);  
        exit;  // Exit if the query fails
    }

    // Check if the result is a valid mysqli_result object
    if (!is_object($result)) {
        echo "Query result is not a mysqli_result object!";
        exit;  // Exit if result is invalid
    }

    return $result;  // Return the valid result set
}

// Function to get publisher name by publisherid
function getPubName($conn, $pubid){
    // Prepare SQL query to fetch publisher name by publisherid
    $query = "SELECT publisher_name FROM publisher WHERE publisherid = '$pubid'";
    $result = mysqli_query($conn, $query);  // Execute query

    if (!$result) {
        // Show error if query fails
        echo "Can't retrieve publisher data: " . mysqli_error($conn);  
        exit;  // Exit if the query fails
    }

    // Check if a publisher with the given id is found
    if (mysqli_num_rows($result) == 0) {
        echo "No publisher found!";
        exit;  // Exit if no publisher found
    }

    // Fetch and return publisher name
    $row = mysqli_fetch_assoc($result);
    return $row['publisher_name'];  
}

// Function to get book price by ISBN
function getBookPrice($conn, $isbn){
    // Prepare SQL query to fetch book price by ISBN
    $query = "SELECT book_price FROM books WHERE book_isbn = '$isbn'";
    $result = mysqli_query($conn, $query);  // Execute query

    if (!$result) {
        // Show error if query fails
        echo "Can't retrieve book price: " . mysqli_error($conn);
        exit;  // Exit if the query fails
    }

    // Fetch and return book price
    $row = mysqli_fetch_assoc($result);
    return $row['book_price'];
}
?>
