<?php
// Start a session
session_start();

// Include database connection
include 'db_connection.php';

// Define the base directory for book images (adjusted for your structure)
$imageBasePath = 'bootstrap/img/'; // 'img' folder inside 'bootstrap'

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
    $sql = "SELECT book_title, book_author, book_images FROM books WHERE book_author = ? AND book_isbn != ? LIMIT 5";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("ss", $author, $lastBookISBN);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display recommended books with images and titles
    echo "<h2>Recommended Books:</h2>";
    if ($result->num_rows > 0) {
        echo "<div style='display: flex; flex-wrap: wrap; gap: 20px;'>";
        while ($book = $result->fetch_assoc()) {
            $bookTitle = htmlspecialchars($book['book_title']);
            $bookAuthor = htmlspecialchars($book['book_author']);
            $bookImage = htmlspecialchars($book['book_images']); // Filename of the book image

            // Debug output
            echo "<p>Debug: Image Path - " . $imageBasePath . $bookImage . "</p>";

            echo "<div style='border: 1px solid #ddd; padding: 10px; text-align: center; width: 150px;'>";
            // Construct the full path to the book image in the 'bootstrap/img' folder
            if (!empty($bookImage)) {
                $imagePath = $imageBasePath . $bookImage;
                echo "<img src='$imagePath' alt='Cover of $bookTitle' class='img-fluid' style='width: 100%; height: auto;'>";
            } else {
                // Display a default image if book image is missing or invalid
                echo "<img src='bootstrap/img/default-image.jpg' alt='Default cover' class='img-fluid' style='width: 100%; height: auto;'>";
            }
            // Display the book title and author
            echo "<p><strong>$bookTitle</strong></p>";
            echo "<p style='color: gray; font-size: 0.9em;'>by $bookAuthor</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No recommendations available.</p>";
    }
} else {
    echo "<p>No recent activity to base recommendations on.</p>";
}

// Close the database connection
$conn->close();
?>
