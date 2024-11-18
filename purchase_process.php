<?php
session_start();
require_once "./functions/database_functions.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate form inputs
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $payment = $_POST['payment'];

    // Check for empty fields
    if (empty($name) || empty($contact) || empty($address)) {
        $_SESSION['err'] = 1; // Set error session variable
        header("Location: checkout.php"); // Redirect back to purchase page
        exit;
    }

    // Connect to the database
    $conn = db_connect();

    // Insert order details into the "orders" table
    $order_date = date("Y-m-d H:i:s");
    $total_price = $_SESSION['total_price'];
    $query = "INSERT INTO orders (customer_name, contact, address, payment_method, total_price, order_date)
              VALUES ('$name', '$contact', '$address', '$payment', '$total_price', '$order_date')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Failed to place order: " . mysqli_error($conn);
        exit;
    }

    // Get the last inserted order ID
    $order_id = mysqli_insert_id($conn);

    // Insert each item into the "order_items" table
    foreach ($_SESSION['cart'] as $book_isbn => $qty) {
        $book = getBookByIsbn($conn, $book_isbn); // Retrieve book details
        $book_price = $book['book_price'];
        $query = "INSERT INTO order_items (order_id, book_isbn, quantity, book_price)
                  VALUES ('$order_id', '$book_isbn', '$qty', '$book_price')";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "Failed to add order items: " . mysqli_error($conn);
            exit;
        }
    }

    // Clear the cart
    unset($_SESSION['cart']);
    unset($_SESSION['total_items']);
    unset($_SESSION['total_price']);

    // Redirect to a success page
    $_SESSION['message'] = "Order placed successfully!";
    header("Location: confirmation.php");
    exit;
}
?>
