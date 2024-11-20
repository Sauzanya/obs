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

// if ($_SESSION['err'] == 0) {
//     header("Location: purchase.php");
//     exit;
// } else {
//     unset($_SESSION['err']);
// }

require_once "./functions/database_functions.php";
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
                <div class="card-title h6 fw-bold">Please Fill out all Fields</div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form id="purchaseForm" method="post" action="purchase.php" class="form-horizontal">
                        <?php if (isset($_SESSION['err']) && $_SESSION['err'] == 1) { ?>
                        <p class="text-danger">All fields have to be filled</p>
                        <?php } ?>

                        <!-- Name -->
                        <div class="form-group mb-3">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control rounded-0" required>
                        </div>
                        <!-- Contact -->
                        <div class="form-group mb-3">
                            <label for="contact" class="control-label">Contact Number</label>
                            <input type="text" name="contact" id="contact" class="form-control rounded-0" required>
                        </div>
                        <!-- Address -->
                        <div class="form-group mb-3">
                            <label for="address" class="control-label">Address</label>
                            <textarea name="address" id="address" class="form-control rounded-0" rows="3" required></textarea>
                        </div>
                        <!-- Payment Method -->
                        <div class="form-group mb-3">
                            <label for="payment" class="control-label">Payment Method</label>
                            <select name="payment" class="form-control rounded-0" id="payment">
                                <option value="cod">Cash on Delivery (COD)</option>
                                <option value="khalti">Khalti</option>
                            </select>
                        </div>
                        <button id="purchaseBtn" class="btn btn-primary" type="submit" name="submit">Purchase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $payment = $_POST['payment'];
        $total_price = $_SESSION['total_price'];
        $customer_id = 1; // Replace with actual customer ID if available

        // Insert the order
        $customer_id = getOrInsertCustomerId($name, $address, $contact);
        insertIntoOrder($conn, $customer_id, $total_price, $order_date, $name, $address, $contact, $payment_method);


        // Insert order items
        foreach ($_SESSION['cart'] as $isbn => $qty) {
            $book = getBookByIsbn($conn, $isbn);
            if ($book) {
                insertOrderItem($order_id, $isbn, $book['book_price'], $qty);
            }
        }

        // Clear the cart and redirect
        unset($_SESSION['cart']);
        unset($_SESSION['total_price']);
        unset($_SESSION['total_items']);
        $_SESSION['message'] = "Order placed successfully!";
        header("Location: index.php");
        exit;
    }
} else {
    echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books in it!</p>";
}
if (isset($conn)) { mysqli_close($conn); }
require_once "./template/footer.php";
?>
