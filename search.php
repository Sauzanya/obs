<?php
// Include database connection
include 'db_connection.php';

// Get the search term from the user
$searchTitle = $_GET['title'] ?? '';  // Assuming the search term is passed as a query parameter

// Check if a search term is provided
if ($searchTitle !== '') {
    // Fetch sorted book records from the database
    $sql = "SELECT * FROM books ORDER BY title ASC";
    $result = $conn->query($sql);

    // Convert database results to an array for binary search
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    // Binary Search Function
    function binarySearch($books, $searchTitle) {
        $left = 0;
        $right = count($books) - 1;

        while ($left <= $right) {
            $mid = (int)(($left + $right) / 2);

            if ($books[$mid]['title'] === $searchTitle) {
                return $books[$mid];  // Book found
            } elseif ($books[$mid]['title'] < $searchTitle) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }
        return null;  // Book not found
    }

    // Perform the binary search
    $book = binarySearch($books, $searchTitle);

    // Display the result
    if ($book) {
        echo "Book found: " . $book['title'] . " by " . $book['author'];
        // You can also display other book details as needed
    } else {
        echo "Book not found.";
    }
} else {
    echo "Please enter a search term.";
}
?>
