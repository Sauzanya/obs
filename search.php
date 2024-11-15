<?php
// Include database connection
include 'db_connection.php';

// Get the search term from the user
$searchTitle = $_GET['title'] ?? '';  // Assuming the search term is passed as a query parameter

// Check if a search term is provided
if ($searchTitle !== '') {
    // SQL query for partial match search with pagination
    $limit = 10; // Number of results per page
    $page = $_GET['page'] ?? 1;
    $offset = ($page - 1) * $limit;

    // Using a prepared statement to prevent SQL injection
    $sql = "SELECT * FROM books WHERE title LIKE ? ORDER BY title ASC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $searchTermWithWildcards = "%" . $conn->real_escape_string($searchTitle) . "%";
    $stmt->bind_param("sii", $searchTermWithWildcards, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display the results
    if ($result->num_rows > 0) {
        while ($book = $result->fetch_assoc()) {
            echo "Book found: " . $book['title'] . " by " . $book['author'] . "<br>";
        }
    } else {
        echo "No books found.";
    }
} else {
    echo "Please enter a search term.";
}
?>
