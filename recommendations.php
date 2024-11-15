<?php
// Start a session
session_start();

// Include database connection
include 'db_connection.php';

// Get the last viewed book from the session
$lastBookISBN = $_SESSION['last_book_isbn'] ?? null;

if ($lastBookISBN) {
    // Step 1: Get the author of the last viewed book
    $sql = "SELECT book_author FROM books WHERE book_isbn = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("s", $lastBookISBN);
    $stmt->execute();
    $result = $stmt->get_result();
    $author = $result->fetch_assoc()['book_author'];

    // Step 2: Fetch recommendations by the same author
    $sql = "SELECT * FROM books WHERE book_author = ? AND book_isbn != ? LIMIT 5";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("ss", $author, $lastBookISBN);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display recommended books
    echo "<h2>Recommended Books:</h2>";
    if ($result->num_rows > 0) {
        while ($book = $result->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($book['book_title']) . "</strong> by " . htmlspecialchars($book['book_author']) . "</p>";
        }
    } else {
        echo "<p>No recommendations available.</p>";
    }
} else {
    echo "<p>No recent activity to base recommendations on.</p>";
}

// Close the database connection
$conn->close();
?>
