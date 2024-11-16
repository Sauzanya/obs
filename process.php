<?php
session_start();

// Check if all fields are filled out
$_SESSION['err'] = 1;
foreach($_POST as $key => $value) {
    if(trim($value) == '') {
        $_SESSION['err'] = 0;
    }
}

// If there's an error (i.e., empty fields), redirect to purchase.php
if($_SESSION['err'] == 0) {
    header("Location: purchase.php");
    exit;
} else {
    unset($_SESSION['err']);
}

require_once "./functions/database_functions.php";
// Connect to the database
$conn = db_connect();

// Handle payment method (if cash on delivery is chosen, proceed, else show error)
$payment_method = isset($_POST['payment']) ? $_POST['payment'] : 'cod'; // Default to cod if not set
if ($payment_method != 'cod') {
    // Show a message that other payment methods are unavailable
    echo '<div class="alert alert-danger rounded-0 my-4">This payment method is not currently available. Please choose Cash on Delivery.</div>';
    exit; // Stop further execution if payment method is not COD
}

// Get the current date and time
$date = date("Y-m-d H:i:s");

// Insert the order into the database (no shipping data needed now)
$customerid = 1; // Use a default customer ID or remove this entirely if customer is not required
insertIntoOrder($conn, $customerid, $_SESSION['total_price'], $date);

// Retrieve the orderid of the newly inserted order
$orderid = getOrderId($conn, $customerid);

// Insert items into the order_items table
foreach ($_SESSION['cart'] as $isbn => $qty) {
    $bookprice = getbookprice($isbn);
    $query = "INSERT INTO order_items VALUES 
    ('$orderid', '$isbn', '$bookprice', '$qty')";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Insert value false!" . mysqli_error($conn);
        exit;
    }
}

// Clear session data for cart
session_unset();

// Show success message
echo '<div class="alert alert-success rounded-0 my-4">Your order has been processed successfully. We\'ll be reaching out to confirm your order. Thanks for choosing Cash on Delivery!</div>';

if (isset($conn)) {
    mysqli_close($conn); // Close database connection
}
require_once "./template/footer.php";
?>
