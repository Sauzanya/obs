<?php
session_start();
require_once "./functions/database_functions.php";
$title = "Checking out";
require "./template/header.php";

// Define the exchange rate (if required, else skip this)
// $exchange_rate = 130; // Example exchange rate (1 USD = 130 NPR)

// $_SESSION['total_items'] = 0;
// $_SESSION['total_price'] = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
?>
    <div class="card rounded-0 shadow mb-3">
        <div class="card-body">
            <div class="container-fluid">
                <table class="table">
                    <tr>
                        <th>Item</th>
                        <th>Price (NPR)</th>
                        <th>Quantity</th>
                        <th>Total (NPR)</th>
                    </tr>
                    <?php
                    foreach ($_SESSION['cart'] as $isbn => $qty) {
                        $conn = db_connect();
                        $book = getBookByIsbn($conn, $isbn);  // Assume this returns an associative array
                        if ($book) {
                            $price_in_npr = $book['book_price'] * $exchange_rate;  // Convert price to NPR if needed
                            $_SESSION['total_items'] += $qty;
                            $_SESSION['total_price'] += $qty * $price_in_npr;  // Calculate total price in NPR
                    ?>
                    <tr>
                        <td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
                        <td><?php echo "₹ " . number_format($price_in_npr, 2); ?></td>  <!-- Show price in NPR -->
                        <td><?php echo $qty; ?></td>
                        <td><?php echo "₹ " . number_format($qty * $price_in_npr, 2); ?></td>  <!-- Show total price in NPR -->
                    </tr>
                    <?php 
                            }
                        } 
                    ?>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th><?php echo $_SESSION['total_items']; ?></th>
                        <th><?php echo "₹ " . number_format($_SESSION['total_price'], 2); ?></th>  <!-- Show total in NPR -->
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12">
            <div class="card rounded-0 shadow">
                <div class="card-header">
                    <div class="card-title h6 fw-bold">Proceed to Purchase</div>
                </div>
                <div class="card-body container-fluid">
                    <p class="fw-light fst-italic"><small class="text-muted">Please click "Purchase" if you wish to confirm your purchase, or <a href="index.php" class="text-decoration-none">Continue Shopping</a> to add or remove items.</small></p>
                    <div class="mb-3 d-grid">
                        <a href="purchase.php" class="btn btn-primary rounded-0">Purchase</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
} else {
    echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books in it!</p>";
}

if (isset($conn)) { mysqli_close($conn); }
require_once "./template/footer.php";
?>
