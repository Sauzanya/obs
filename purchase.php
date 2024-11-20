<?php
session_start();

// Server-Side Form Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Validate the input fields
    if (empty($_POST['name']) || empty($_POST['address']) || empty($_POST['contact'])) {
        $_SESSION['err'] = 0;
        header("Location: purchase.php");
        exit;  // Prevent further code execution after redirect
    }

    // Process the order
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $payment = $_POST['payment'];
    $total_price = $_SESSION['total_price'];
    $order_date = date('Y-m-d H:i:s'); // Current date and time
    $customer_id = 1; // Replace with actual customer ID if available

    // Insert the order into the database
    $conn = db_connect();
    $customer_id = getOrInsertCustomerId($name, $address, $contact);
    $order_id = insertIntoOrder($conn, $customer_id, $total_price, $order_date, $name, $address, $contact, $payment);

    // Insert order items
    foreach ($_SESSION['cart'] as $isbn => $qty) {
        $book = getBookByIsbn($conn, $isbn);
        if ($book) {
            insertOrderItem($order_id, $isbn, $book['book_price'], $qty);
        }
    }

    // Clear cart after placing the order
    unset($_SESSION['cart']);
    unset($_SESSION['total_price']);
    unset($_SESSION['total_items']);

    // Set a success message and redirect
    $_SESSION['message'] = "Order placed successfully! We will reach out to you soon.";
    header("Location: index.php");  // Redirect to index or any other page
    exit;  // Prevent further execution after the redirect
}

require_once "./template/header.php";  // Now, you can safely include your header after handling the POST logic

// Display any session messages after header has been sent
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);  // Clear the message after displaying it
}

// Check if cart is available
if (isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))) {
    // Display cart details and the form
    // (Rest of the HTML and form goes here...)
} else {
    // Display a warning message if the cart is empty
    echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books to it!</p>";
}

if (isset($conn)) {
    mysqli_close($conn);  // Close database connection if open
}

require_once "./template/footer.php";  // Include the footer as well
?>

<script>
// Client-Side Form Validation
function validateForm() {
    var name = document.getElementById("name").value;
    var contact = document.getElementById("contact").value;
    var address = document.getElementById("address").value;

    if (name == "" || contact == "" || address == "") {
        alert("All fields are required. Please fill them out.");
        return false;
    }
    return true;
}
</script>
