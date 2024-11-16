<?php
session_start();
$_SESSION['err'] = 1;
foreach ($_POST as $key => $value) {
    if (trim($value) == '') {
        $_SESSION['err'] = 0;
    }
    break;
}

if ($_SESSION['err'] == 0) {
    header("Location: checkout.php");
} else {
    unset($_SESSION['err']);
}

$_SESSION['ship'] = array();
foreach ($_POST as $key => $value) {
    if ($key != "submit") {
        $_SESSION['ship'][$key] = $value;
    }
}
require_once "./functions/database_functions.php";
// print out header here
$title = "Purchase";
require "./template/header.php";
// connect database
?>
<h4 class="fw-bolder text-center">Payment</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>
<?php
if (isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))) {
?>
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
                <tr>
                    <td>Shipping</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>20.00</td>
                </tr>
                <tr>
                    <th>Total Including Shipping</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th><?php echo "Rs." . ($_SESSION['total_price'] + 20); ?></th>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-header">
                <div class="card-title h6 fw-bold">Please Fill out all Fields </div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form method="post" action="process.php" class="form-horizontal">
                        <?php if (isset($_SESSION['err']) && $_SESSION['err'] == 1) { ?>
                        <p class="text-danger">All fields have to be filled</p>
                        <?php } ?>
                        <div class="form-group mb-3">
                            <div class="mb-3">
                                <!-- Payment Method Selection -->
                                <div class="mb-3">
                                    <label for="payment" class="control-label">Payment Method</label>
                                    <select name="payment" class="form-control rounded-0" id="payment" onchange="checkPayment()">
                                        <option value="cod">Cash on Delivery (COD)</option>
                                        <option value="khalti">Khalti</option>
                
                                    </select>
                                </div>

                                <div id="message" class="text-danger" style="display: none;">
                                    <p>This payment method is not currently available.</p>
                                </div>

                                <!-- Change to 'submit' type to enable form submission -->
                                <button id="purchaseBtn" class="btn btn-primary" type="submit" name="purchaseBtn" disabled>Purchase</button>

                                <div id="orderMessage" class="text-success" style="display: none;">
                                    <p>Your order has been placed, and you will get a call for delivery.</p>
                                </div>

                                <script>
                                function checkPayment() {
                                    var paymentMethod = document.getElementById("payment").value;
                                    var messageDiv = document.getElementById("message");
                                    var purchaseBtn = document.getElementById("purchaseBtn");

                                    if (paymentMethod === "cod") {
                                        messageDiv.style.display = "none";
                                        purchaseBtn.disabled = false;
                                    } else if (paymentMethod === "khalti") {
                                        messageDiv.style.display = "block";
                                        purchaseBtn.disabled = true;
                                    }
                                }

                                // Function to show the order confirmation message when the button is clicked
                                function placeOrder() {
                                    var orderMessage = document.getElementById("orderMessage");
                                    orderMessage.style.display = "block";
                                    
                                    // Disable purchase button after confirming order
                                    var purchaseBtn = document.getElementById("purchaseBtn");
                                    purchaseBtn.disabled = true;
                                }
                                </script>

                            </div>
                        </div>
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
