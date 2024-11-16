<?php
session_start();
require_once "./functions/database_functions.php";
$title = "Checking out";
require "./template/header.php";

// Define the exchange rate (if required, else skip this)
$exchange_rate = 130; // Example exchange rate (1 USD = 130 NPR)

// Initialize total items and price
$_SESSION['total_items'] = 0;
$_SESSION['total_price'] = 0;

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
                    <div class="card-title h6 fw-bold">Please Fill the following form</div>
                </div>
                <div class="card-body container-fluid">
                    <form method="post" action="purchase.php" class="form-horizontal">
                        <?php if (isset($_SESSION['err']) && $_SESSION['err'] == 1) { ?>
                            <p class="text-danger">All fields have to be filled</p>
                        <?php } ?>
                        <div class="mb-3">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" class="form-control rounded-0">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="control-label">Address</label>
                            <input type="text" name="address" class="form-control rounded-0">
                        </div>
                        <div class="mb-3">
                            <label for="Contact" class="control-label">Contact</label>
                            <input type="text" name="Contact" class="form-control rounded-0">
                        </div>
                        <div class="mb-3 d-grid">
                            <input type="submit" name="submit" value="Purchase" class="btn btn-primary rounded-0">
                        </div>
                    </form>
                    <p class="fw-light fst-italic"><small class="text-muted">Please press Purchase to confirm your purchase, or Continue Shopping to add or remove items.</small></p>
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
