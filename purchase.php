<?php
session_start();
// Include the database functions
include_once 'functions/database_functions.php'; // Ensure the path is correct

$conn = db_connect();

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['message'] = "Your cart is empty. Please add some items to proceed.";
    header("Location: checkout.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_btn'])) {

    // Validate the input fields
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $payment = trim($_POST['payment']);

    $errors = [];

    // Name validation (only letters and spaces allowed)
    if (empty($name) || !preg_match("/^[a-zA-Z\s]+$/", $name)) {
         $errors[] = "Name is required and should only contain letters and spaces.";
    }

    // Contact validation (10-digit number)
    if (empty($contact) || !preg_match("/^\d{10}$/", $contact))
     {
        $errors[] = "Contact number is required and must be a valid 10-digit number.";
    }

    // Address validation (required)
    if (empty($address))
     {
        $errors[] = "Address is required.";
    }

    // Payment method validation (required)
    if (empty($payment)) {
        // $errors[] = "Please select a payment method.";
    }

    // If validation fails, redirect with errors
    if (!empty($errors)) {
        $_SESSION['message'] = implode("<br><br>", $errors);
        $_SESSION['err'] = 1; // Set error flag
        header("Location: purchase.php");
        exit;
    }

    // Process the order if validation passes
    $total_price = $_SESSION['total_price'];
    $order_date = date('Y-m-d H:i:s'); // Current date and time
    
    // Insert customer details
    $customer_id = getOrInsertCustomerId($name, $address, $contact);

    if (!$customer_id) {
        $_SESSION['message'] = "Failed to insert customer details. Please try again.";
        header("Location: purchase.php");
        exit;
    }

    // Insert the order into the database
    $order_id = insertIntoOrder($conn, $customer_id, $total_price,  $order_date, $payment);

    if (!$order_id) {
        $_SESSION['message'] = "Failed to process your order. Please try again.";
        header("Location: purchase.php");
        exit;
    }

    // Insert order items
    foreach ($_SESSION['cart'] as $isbn => $qty) {
        $book = getBookByIsbn($conn, $isbn);
       
        if ($book) {
            $inserted = insertOrderItems($order_id, $isbn, $book['book_price'], $qty);
            if (!$inserted) {
                $_SESSION['message'] = "Failed to process order items. Please try again.";
                header("Location: purchase.php");
                exit;
            }
        }
    }

    // Clear cart after placing the order
    unset($_SESSION['cart']);
    unset($_SESSION['total_price']);
    unset($_SESSION['total_items']);

    // Set a success message and redirect
    $_SESSION['message'] = "Order placed successfully! We will contact you soon.";
    header("Location: index.php");
    exit;
}

// Display success or error messages


// Include the header
require_once "./template/header.php";
?>

<?php if (isset($_SESSION['cart']) && array_count_values($_SESSION['cart'])) {    ?>
    <h4 class="fw-bolder text-center">Payment</h4>
    <div class="card rounded-0 shadow mb-3">
        <div class="card-body">
            <div class="container-fluid">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $isbn => $qty) { 
                            $book = getBookByIsbn($conn, $isbn);
                            if ($book) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($book['book_title']) . " by " . htmlspecialchars($book['book_author']); ?></td>
                                    <td><?php echo "₹" . number_format($book['book_price'], 2); ?></td>
                                    <td><?php echo $qty; ?></td>
                                    <td><?php echo "₹" . number_format($qty * $book['book_price'], 2); ?></td>
                                </tr>
                           <?php } ?>
                        <?php } ?>
                        <tr>
                            <th colspan="2"></th>
                            <th>Total Items</th>
                            <th><?php echo $_SESSION['total_items']; ?></th>
                        </tr>
                        <tr>
                            <th colspan="2"></th>
                            <th>Total Price</th>
                            <th><?php echo "₹" . number_format($_SESSION['total_price'], 2); ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php 
    if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-info text-center">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}
?>
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12">
            <div class="card rounded-0 shadow">
                <div class="card-header">
                    <div class="card-title h6 fw-bold">Please Fill out all Fields</div>
                </div>
                <div class="card-body">
                    <form id="purchaseForm" method="POST" action="purchase.php" onsubmit="return validateForm()">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control rounded-0" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="contact">Contact Number</label>
                            <input type="text" name="contact" id="contact" class="form-control rounded-0" required pattern="^\d{10}$" title="Please enter a valid 10-digit contact number.">
                        </div>
                        <div class="form-group mb-3">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" class="form-control rounded-0" rows="3" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="payment">Payment Method</label>
                            <select name="payment" class="form-control rounded-0" id="paymentOptions" required>
                                <option value="">Select Payment Method</option>
                                <option value="cod">Cash on Delivery (COD)</option>
                                <option value="khalti" disabled>Khalti (Unavailable)</option>
                            </select>
                        </div>
                        <button id="purchaseBtn" class="btn btn-primary" type="submit" name="submit_btn">Purchase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <p class="text-warning text-center">Your cart is empty! Please add some items to proceed.</p>
<?php } ?>

<?php
require_once "./template/footer.php";
if (isset($conn)) { mysqli_close($conn); }
?>
