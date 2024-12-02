<?php
session_start();
require_once "./functions/database_functions.php";
$title = "Checking Out";
require "./template/header.php";
// Ensure the cart exists and is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $_SESSION['total_items'] = 0;      // Initialize total items
    $_SESSION['total_price'] = 0;     // Initialize total price
?>
    <div class="card rounded-0 shadow mb-3">
        <div class="card-body">
            <div class="container-fluid">
                <h4 class="fw-bold text-center mb-4">Your Cart</h4>
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
                        <?php
                        foreach ($_SESSION['cart'] as $isbn => $qty) {
                            $conn = db_connect();
                            $book = getBookByIsbn($conn, $isbn); // Fetch book details by ISBN
                            if ($book) {
                                $price = $book['book_price']; // Book price
                                $total = $price * $qty;       // Calculate total for the item
                                $_SESSION['total_items'] += $qty;   // Update total items
                                $_SESSION['total_price'] += $total; // Update total price
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['book_title']) . " by " . htmlspecialchars($book['book_author']); ?></td>
                            <td><?php echo "₹ " . number_format($price, 2); ?></td>
                            <td><?php echo $qty; ?></td>
                            <td><?php echo "₹ " . number_format($total, 2); ?></td>
                        </tr>
                        <?php
                            } // End if book exists
                        } // End foreach
                        ?>
                        <tr>
                            <th colspan="2" class="text-end">Total Items:</th>
                            <td><?php echo $_SESSION['total_items']; ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Total Price:</th>
                            <th><?php echo "₹ " . number_format($_SESSION['total_price'], 2); ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12">
            <div class="card rounded-0 shadow">
                <div class="card-header">
                    <h5 class="card-title fw-bold">Proceed to Purchase</h5>
                </div>
                <div class="card-body">
                    <p class="fw-light fst-italic text-muted">
                        Click "Purchase" to confirm your order, or <a href="index.php" class="text-decoration-none">Continue Shopping</a> to add more items.
                    </p>
                    <form action="purchase.php" method="POST" class="d-grid">
                        <input type="hidden" name="submit" value="1"> <!-- Hidden input for 'submit' parameter -->
                        <button type="submit" class="btn btn-primary rounded-0">Purchase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    // If the cart is empty, display a warning
    echo "<div class='alert alert-warning text-center'>Your cart is empty! Please add some books to it.</div>";
}

// Close the database connection if it's open
if (isset($conn)) {
    mysqli_close($conn);
}

// Include the footer
require_once "./template/footer.php";
?>
