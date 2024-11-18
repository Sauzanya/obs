<?php
session_start();  

if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);  
}

$_SESSION['err'] = 1;
foreach ($_POST as $key => $value) {
    if (trim($value) == '') {
        $_SESSION['err'] = 0;  
        break;  
    }
}

if ($_SESSION['err'] == 0) {
    header("Location:cart.php");
    exit;
} else {
    unset($_SESSION['err']);
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
                    $book = getBookByIsbn($conn, $isbn); 
                    if ($book) { 
                ?>
                <tr>
                    <td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
                    <td><?php echo "Rs." . $book['book_price']; ?></td>
                    <td><?php echo $qty; ?></td>
                    <td><?php echo "Rs." . $qty * $book['book_price']; ?></td>
                </tr>
                <?php 
                    }
                } ?>
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
                <div class="card-title h6 fw-bold">Please Choose the Payment Option</div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form id="purchaseForm" method="post" action="purchase_process.php" class="form-horizontal">
                        

                        <!-- Payment Method -->
                        <div class="form-group mb-3">
                            <label for="payment" class="control-label">Payment Method</label>
                            <select name="payment" class="form-control rounded-0" id="payment" onchange="checkPayment()">
                                <option value="cod">Cash on Delivery (COD)</option>
                                <option value="khalti">Khalti</option>
                            </select>
                        </div>

                        <div id="paymentMessage" class="alert alert-danger" style="display: none;">
                            This payment method is not currently available. Please choose Cash on Delivery.
                        </div>

                        <button id="purchaseBtn" class="btn btn-primary" type="button" onclick="handlePurchase()">Purchase</button>
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
function checkPayment() {
    var paymentMethod = document.getElementById("payment").value;
    var purchaseBtn = document.getElementById("purchaseBtn");
    var paymentMessage = document.getElementById("paymentMessage");

    if (paymentMethod === "cod") {
        purchaseBtn.disabled = false;
        paymentMessage.style.display = "none";
    } else if (paymentMethod === "khalti") {
        purchaseBtn.disabled = true;
        paymentMessage.style.display = "block";
    }
}

window.onload = checkPayment;

function handlePurchase() {
    var purchaseBtn = document.getElementById("purchaseBtn");
    var paymentMethod = document.getElementById("payment").value;

    if (paymentMethod === "cod") {
        alert("Your order has been successfully placed. We'll reach out to confirm your order. Thank you for choosing Cash on Delivery!");
        document.getElementById("purchaseForm").submit();
    } else {
        alert("This payment method is not available. Please choose Cash on Delivery.");
    }
}
</script>
