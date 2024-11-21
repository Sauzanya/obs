<?php
session_start();
// Include the database functions
include_once 'functions/database_functions.php';  // Make sure this path is correct

$conn = db_connect();  // Now this function should work

    // Server-Side Form Handling
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

        // Validate the input fields
        if (empty($_POST['name']) || empty($_POST['address']) || empty($_POST['contact'])) {
            $_SESSION['err'] = 0;
            header("Location: purchase.php");
            exit;  // Prevent further code execution after redirect
        }

        // Process the order
        $name    = $_POST['name'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $payment = $_POST['payment'];
        $total_price = $_SESSION['total_price'];
        $order_date = date('Y-m-d H:i:s'); // Current date and time

        // Insert customer details
        $customer_id = getOrInsertCustomerId($name, $address, $contact);
        // Insert the order into the database
        $order_id = insertIntoOrder($conn, $customer_id, $total_price, $order_date, $payment);

        // Insert order items
        foreach ($_SESSION['cart'] as $isbn => $qty) {
            $book = getBookByIsbn($conn, $isbn);
            if ($book) {
                insertOrderItem($order_id, $isbn, $book['book_price'], $qty);
            }
        }

        // Clear cart after placing the order
        unset($_SESSION['cart']);
        unset($_SESSION['total_price']);
        unset($_SESSION['total_items']);

        // Set a success message and redirect
        $_SESSION['message'] = "Order placed successfully! We will reach out to you soon.";
        header("Location: index.php");  // Redirect back to the same page to show the message
        exit;  // Prevent further execution after the redirect
    }

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

    // Display any session messages after header has been sent
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);  // Clear the message after displaying it
    }
    
    if ($_SESSION['err'] == 0) {
        header("Location: checkout.php");
        exit;
    } else {
        unset($_SESSION['err']);
    }
    require_once "./template/header.php";  // Now, you can safely include your header after handling the POST logic

?>

<?php
    if (isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))) {
                ?>
                <h4 class="fw-bolder text-center">Payment</h4>
                
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
                                        <?php } // end of if ?>
                                            
                                    </div>
                                         <?php } // end of foreach?>
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
                                            <form id="purchaseForm" method="POST" action="purchase.php" class="form-horizontal" onsubmit="return validateForm()">
                                                <?php if (isset($_SESSION['err']) && $_SESSION['err'] == 0) { ?>
                                                <p class="text-danger">All fields are required. Please fill in all fields!</p>
                                                <?php } ?>

                                                <!-- Name -->
                                                <div class="form-group mb-3">
                                                    <label for="name" class="control-label">Name</label>
                                                    <input type="text" name="name" id="name" class="form-control rounded-0" required>
                                                </div>
                                                <!-- Contact -->
                                                <div class="form-group mb-3">
                                                    <label for="contact" class="control-label">Contact Number</label>
                                                    <input 
                                                        type="text" 
                                                        name="contact" 
                                                        id="contact" 
                                                        class="form-control rounded-0" 
                                                        required
                                                        pattern="^\d{10}$" 
                                                        title="Please enter a valid 10-digit contact number."
                                                        placeholder="Enter 10-digit number"
                                                    >
                                                </div>
                                                <!-- Address -->
                                                <div class="form-group mb-3">
                                                    <label for="address" class="control-label">Address</label>
                                                    <textarea name="address" id="address" class="form-control rounded-0" rows="3" required></textarea>
                                                </div>
                                                <!-- Payment Method -->
                                                <div class="form-group mb-3">
                                                    <label for="payment" class="control-label">Payment Method</label>
                                                    <select name="payment" class="form-control rounded-0" id="paymentOptions" required>
                                                        <option value="cod">Cash on Delivery (COD)</option>
                                                        <option value="khalti">Khalti</option>
                                                    </select>
                                                </div>
                                                <!-- Message for Khalti -->
                                                <div id="paymentMessage"></div>
                                                <button id="purchaseBtn" class="btn btn-primary" type="submit" name="submit">Purchase</button>
                                            </form>
                                        </div>
                                </div>


                           
                                </div>
                                </div>
                            </div>

                        </div>
            <?php
                    }
                    else {
                        // If cart is empty, display a warning message
                        echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books to it!</p>";
                    }

        require_once "./template/footer.php";  // Include the footer as well
        if (isset($conn)) { mysqli_close($conn); }
        require_once "./template/footer.php";

?>

<script>
    // Client-Side Form Validation
    function validateForm() {
        // alert('called validation form');
        var name = document.getElementById("name").value;
        var contact = document.getElementById("contact").value;
        var address = document.getElementById("address").value;

        if (name == "" || contact == "" || address == "") {
            alert("All fields are required. Please fill them out.");
            return false;
        }
        return true;
    }

    // Detect the payment method change and disable the button if "Khalti" is selected
    document.getElementById("paymentOptions").addEventListener("change", function() {
        var paymentMethod = document.getElementById("paymentOptions").value;
            var purchaseBtn = document.getElementById("purchaseBtn");
            var paymentMessage = document.getElementById("paymentMessage");

            if (paymentMethod === "cod") {
                
                purchaseBtn.disabled = false;
                paymentMessage.style.display = "none";
            } else if (paymentMethod === "khalti") {
                
                    purchaseBtn.disabled = true;
                    paymentMessage.style.display = "block";
                        paymentMessage.innerHTML = "<p class='text-danger'>Khalti payment option is not available at the moment.</p>"; // Show message
            }
            return true;
    });
 
</script>