<?php
// Include the database connection
include 'db_connection.php';

// Assume the logged-in user's ID
$user_id = 1;

// Step 1: Get the last book the user interacted with
$sql = "SELECT book_id FROM user_activity WHERE user_id = ? ORDER BY timestamp DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$lastBookId = $result->fetch_assoc()['book_id'];

if ($lastBookId) {
    // Step 2: Get the author of the last interacted book
    $sql = "SELECT book_author FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lastBookId);
    $stmt->execute();
    $result = $stmt->get_result();
    $author = $result->fetch_assoc()['book_author'];

    // Step 3: Fetch books by the same author
    $sql = "SELECT book_title, book_author FROM books WHERE book_author = ? AND book_id != ? LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $author, $lastBookId);
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
