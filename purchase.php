<?php
session_start();

// Display any message set in the session
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);  // Clear the message after it's displayed
}

require_once "./functions/database_functions.php";
// print out header here
$title = "Purchase";
require "./template/header.php";

// check if there are items in the cart
if (isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))) {
?>
<h4 class="fw-bolder text-center">Payment</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<div class="card rounded-0 shadow mb-3">
    <div class="card-body">
        <div class="container-fluid">
            <table class="table">
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                <?php
                foreach ($_SESSION['cart'] as $isbn => $qty) {
                    $conn = db_connect();
                    $book = mysqli_fetch_assoc(getBookByIsbn($conn, $isbn));
                ?>
                <tr>
                    <td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
                    <td><?php echo "Rs." . $book['book_price']; ?></td>
                    <td><?php echo $qty; ?></td>
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
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-header">
                <div class="card-title h6 fw-bold">Please Select Payment Method</div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form method="post" action="process.php" class="form-horizontal">
                        <div class="form-group mb-3">
                            <label for="payment" class="control-label">Payment Method</label>
                            <select name="payment" class="form-control rounded-0" id="payment">
                                <option value="cod">Cash on Delivery (COD)</option>
                                <option value="khalti">Khalti</option>
                            </select>
                        </div>

                        <button id="purchaseBtn" class="btn btn-primary" type="submit" name="purchaseBtn" disabled>Purchase</button>
                    </form>
                    <p class="fw-light fst-italic"><small class="text-muted">Please press Purchase to confirm your purchase, or Continue Shopping to add or remove items.</small></p>
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

<script>
// Function to handle payment method selection and enable/disable the purchase button
function checkPayment() {
    var paymentMethod = document.getElementById("payment").value;
    var purchaseBtn = document.getElementById("purchaseBtn");

    if (paymentMethod === "cod") {
        // Enable the purchase button for Cash on Delivery
        purchaseBtn.disabled = false;
    } else if (paymentMethod === "khalti") {
        // Disable the purchase button for Khalti (since it's unavailable)
        purchaseBtn.disabled = true;
    }
}
</script>
