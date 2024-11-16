<?php
session_start();
$_SESSION['err'] = 1;

// Validate form input
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
                        $result = getBookByIsbn($conn, $isbn);  // Ensure this returns a mysqli_result

                        // Ensure the query was successful and fetch the book details
                        if ($result && $book = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
                            <td><?php echo "Rs." . $book['book_price']; ?></td>
                            <td><?php echo $qty; ?></td>
                            <td><?php echo "Rs." . ($qty * $book['book_price']); ?></td>
                        </tr>
                    <?php
                        } else {
                            echo "<tr><td colspan='4'>Error fetching book details for ISBN: $isbn</td></tr>";
                        }
                    }
                    ?>
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
                    <div class="card-title h6 fw-bold">Please Fill out all Fields</div>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <form method="post" action="process.php" class="form-horizontal">
                            <?php if (isset($_SESSION['err']) && $_SESSION['err'] == 1) { ?>
                                <p class="text-danger">All fields have to be filled</p>
                            <?php } ?>
                            <div class="form-group mb-3">
                                <label for="payment_type" class="control-label">Payment Method</label>
                                <select class="form-select rounded-0" name="payment_type" id="payment_type" onchange="togglePaymentOptions()">
                                    <option value="COD">Cash on Delivery (COD)</option>
                                    <option value="Khalti">Khalti</option>
                                </select>
                            </div>
                            <div id="khalti-message" style="display:none;" class="text-warning">
                                Khalti payment method is currently unavailable.
                            </div>
                            <div class="form-group mb-3">
                                <label for="name" class="control-label">Name</label>
                                <input type="text" name="name" class="form-control rounded-0">
                            </div>
                            <div class="form-group mb-3">
                                <label for="address" class="control-label">Address</label>
                                <input type="text" name="address" class="form-control rounded-0">
                            </div>
                            <div class="form-group mb-3">
                                <label for="Contact" class="control-label">Contact</label>
                                <input type="text" name="Contact" class="form-control rounded-0">
                            </div>
                            <div class="form-group mb-3 d-grid">
                                <button type="submit" class="btn btn-primary rounded-0" id="purchase-btn" disabled>Purchase</button>
                                <button type="reset" class="btn btn-default bg-light bg-gradient border rounded-0">Cancel</button>
                            </div>
                        </form>
                        <p class="fw-light fst-italic"><small class="text-muted">Please press Purchase to confirm your purchase, or Continue Shopping to add or remove items.</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enable or disable the purchase button based on the selected payment method
        function togglePaymentOptions() {
            const paymentType = document.getElementById('payment_type').value;
            const purchaseBtn = document.getElementById('purchase-btn');
            const khaltiMessage = document.getElementById('khalti-message');

            if (paymentType === 'Khalti') {
                khaltiMessage.style.display = 'block';
                purchaseBtn.disabled = true;
            } else {
                khaltiMessage.style.display = 'none';
                purchaseBtn.disabled = false;
            }
        }

        // Initial call to set the correct display on page load
        togglePaymentOptions();
    </script>

<?php
} else {
    echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books in it!</p>";
}

if (isset($conn)) { mysqli_close($conn); }
require_once "./template/footer.php";
?>
