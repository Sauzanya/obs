<?php
session_start();

// Check if all fields are filled out
$_SESSION['err'] = 1;
foreach ($_POST as $key => $value) {
    if (trim($value) == '') {
        $_SESSION['err'] = 0;  // If a field is empty, set error flag
        break;  // Stop checking further fields
    }
}

// If there’s an error (i.e., empty fields), redirect to purchase.php
if ($_SESSION['err'] == 0) {
    $_SESSION['message'] = "All fields are required. Please fill them out!";
    header("Location: purchase.php");
    exit;
} else {
    unset($_SESSION['err']);
}

require_once "./functions/database_functions.php";
// Connect to the database
$conn = db_connect();

// Handle payment method validation (Only COD is allowed)
$payment_method = isset($_POST['payment']) ? $_POST['payment'] : 'cod'; // Default to cod if not set
if ($payment_method != 'cod') {
    $_SESSION['message'] = "This payment method is not available. Please choose Cash on Delivery.";
    header("Location: purchase.php");
    exit; // Stop further execution if payment method is not COD
}

// Insert the order into the database (without shipping/card info)
$date = date("Y-m-d H:i:s");

// Find customer ID (as there is no shipping, customer info is needed)
$customerid = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null; // Use existing session info

// Validate customer ID
if (!$customerid) {
    $_SESSION['message'] = "Customer information is missing. Please try again.";
    header("Location: purchase.php");
    exit;
}

// Insert the order into the database
insertIntoOrder($conn, $customerid, $_SESSION['total_price'], $date);

// Retrieve the order ID of the newly inserted order
$orderid = getOrderId($conn, $customerid);

// Insert items into the order_items table
foreach ($_SESSION['cart'] as $isbn => $qty) {
    $bookprice = getbookprice($isbn);
    $query = "INSERT INTO order_items VALUES ('$orderid', '$isbn', '$bookprice', '$qty')";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        $_SESSION['message'] = "There was an error processing your order. Please try again.";
        header("Location: purchase.php");
        exit;
    }
}

// Clear session data for cart
session_unset();

// Set success message after order is processed
$_SESSION['message'] = "Your order has been processed successfully. We’ll be reaching out to confirm your order. Thanks for choosing Cash on Delivery!";

// Redirect to purchase.php to show success message
header("Location: purchase.php");
exit;

?>
