<?php
// Start the session
session_start();

// Include necessary files
require_once "./functions/database_functions.php";
require_once "./functions/cart_functions.php";

// Get the book ISBN from form submission
if (isset($_POST['bookisbn'])) {
    $book_isbn = $_POST['bookisbn'];
}

// Initialize the cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
    $_SESSION['total_items'] = 0;
    $_SESSION['total_price'] = '0.00';
}

// Add book to the cart
if (isset($book_isbn)) {
    if (!isset($_SESSION['cart'][$book_isbn])) {
        $_SESSION['cart'][$book_isbn] = 1; // Add book with quantity = 1
    } elseif (isset($_POST['cart'])) {
        if ($_SESSION['cart'][$book_isbn] < 10) { // Allow increment only if < 10
            $_SESSION['cart'][$book_isbn]++;
        }
        unset($_POST);
    }
}

// Save changes to cart quantities
if (isset($_POST['save_change'])) {
    foreach ($_SESSION['cart'] as $isbn => $qty) {
        $new_qty = intval($_POST[$isbn]);

        // Enforce quantity limits
        if ($new_qty < 1) {
            $_SESSION['cart'][$isbn] = 1; // Minimum quantity is 1
        } elseif ($new_qty > 10) {
            $_SESSION['cart'][$isbn] = 10; // Maximum quantity is 10
        } else {
            $_SESSION['cart'][$isbn] = $new_qty; // Update to valid quantity
        }

        // Optional: Remove the item if quantity is explicitly set to 0
        if ($new_qty == 0) {
            unset($_SESSION['cart'][$isbn]);
        }
    }
}

// Set page title and include header
$title = "Your shopping cart";
require "./template/header.php";
?>
<h4 class="fw-bolder text-center">Cart List</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<?php
if (isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))) {
    $_SESSION['total_price'] = total_price($_SESSION['cart']);
    $_SESSION['total_items'] = total_items($_SESSION['cart']);
?>
    <div class="card rounded-0 shadow">
        <div class="card-body">
            <div class="container-fluid">
                <form action="cart.php" method="post" id="cart-form">
                    <table class="table">
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                        <?php
                        foreach ($_SESSION['cart'] as $isbn => $qty) {
                            // Fetch book data from the database
                            $conn = db_connect();
                            $book = getBookByIsbn($conn, $isbn); // Assuming this function returns an associative array
                        ?>
                        <tr>
                            <td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
                            <td><?php echo "Rs." . $book['book_price']; ?></td>
                            <td>
                                <!-- Quantity Input with Validation -->
                                <input 
                                    type="number" 
                                    value="<?php echo $qty; ?>" 
                                    min="1" 
                                    max="10" 
                                    size="2" 
                                    name="<?php echo $isbn; ?>" 
                                    oninput="validateQuantity(this)">
                            </td>
                            <td><?php echo "Rs." . $qty * $book['book_price']; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th><?php echo $_SESSION['total_items']; ?></th>
                            <th><?php echo "Rs." . $_SESSION['total_price']; ?></th>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="card-footer text-end">
            <input type="submit" class="btn btn-primary rounded-0" name="save_change" value="Save Changes" form="cart-form">
            <a href="checkout.php" class="btn btn-dark rounded-0">Go To Checkout</a> 
            <a href="books.php" class="btn btn-warning rounded-0">Continue Shopping</a>
        </div>
    </div>
<?php
} else {
?>
    <div class="alert alert-warning rounded-0">Your cart is empty! Please add at least 1 book to purchase first.</div>
<?php
}

// Close the database connection
if (isset($conn)) {
    mysqli_close($conn);
}

require_once "./template/footer.php";
?>

<script>
// Client-Side Validation for Quantity Input
function validateQuantity(input) {
    let value = parseInt(input.value);
    if (isNaN(value) || value < 1) {
        input.value = 1; // Reset to minimum if invalid
    } else if (value > 10) {
        input.value = 10; // Reset to maximum if it exceeds 10
    }
}
</script>
