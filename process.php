<?php
session_start();

// Check if all fields are filled out
$_SESSION['err'] = 1;
foreach ($_POST as $key => $value) {
    if (trim($value) == '') {
        $_SESSION['err'] = 0;
    }
    break;
}

// If there's an error (i.e., empty fields), redirect to purchase.php
if ($_SESSION['err'] == 0) {
    header("Location: purchase.php");
    exit;
} else {
    unset($_SESSION['err']);
}

require_once "./functions/database_functions.php";
// connect to database
$conn = db_connect();
extract($_SESSION['ship']); // Get customer details from session

// Find customer ID from session or address data
$customerid = getCustomerId($name, $address, $city);
if ($customerid == null) {
    // If customer doesn't exist, insert customer into database and return customerid
    $customerid = setCustomerId($name, $address, $city);
}

// Validate that customerid is a valid integer
if (!$customerid || !is_numeric($customerid)) {
    echo "Invalid customer ID!"; // Or redirect the user if necessary
    exit;
}

// Get the current date and time
$date = date("Y-m-d H:i:s");

// Handle payment method (if cash on delivery is chosen, proceed, else show error)
$payment_method = isset($_POST['payment']) ? $_POST['payment'] : 'cod'; // Default to cod if not set
if ($payment_method != 'cod') {
    // Show a message that other payment methods are unavailable
    echo '<div class="alert alert-danger rounded-0 my-4">This payment method is not currently available. Please choose Cash on Delivery.</div>';
    exit; // Stop further execution if payment method is not COD
}

// Insert the order into the database using prepared statements
$stmt = $conn->prepare("INSERT INTO orders (customerid, total_price, order_date, name, address, city) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("idssss", $customerid, $_SESSION['total_price'], $date, $name, $address, $city);
if (!$stmt->execute()) {
    echo "Error inserting order: " . $stmt->error;
    exit;
}

// Retrieve the orderid of the newly inserted order
$orderid = $conn->insert_id;  // Get the last inserted ID (order ID)

// Insert items into the order_items table using prepared statements
$stmt_item = $conn->prepare("INSERT INTO order_items (orderid, isbn, book_price, quantity) VALUES (?, ?, ?, ?)");
foreach ($_SESSION['cart'] as $isbn => $qty) {
    $bookprice = getbookprice($isbn);
    $stmt_item->bind_param("isdi", $orderid, $isbn, $bookprice, $qty);
    if (!$stmt_item->execute()) {
        echo "Error inserting order item: " . $stmt_item->error;
        exit;
    }
}

// Clear session data for cart
session_unset();
?>

<div class="alert alert-success rounded-0 my-4">
    Your order has been processed successfully. We'll be reaching out to confirm your order. Thanks for choosing Cash on Delivery!
</div>

<?php
if (isset($conn)) {
    mysqli_close($conn); // Close database connection
}
require_once "./template/footer.php";
?>
