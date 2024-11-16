<?php
    // Start the session
    session_start();

    // Include necessary functions
    require_once "./functions/database_functions.php";
    require_once "./functions/cart_functions.php";

    // Function to sanitize input
    function sanitizeInput($input) {
        return htmlspecialchars(trim($input));
    }

    // Handle adding books to the cart
    if(isset($_POST['bookisbn'])){
        $book_isbn = sanitizeInput($_POST['bookisbn']);  // Sanitize the ISBN input

        // Initialize the cart if not already set
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
            $_SESSION['total_items'] = 0;
            $_SESSION['total_price'] = '0.00';
        }

        // Add the book to the cart or increment the quantity
        if(!isset($_SESSION['cart'][$book_isbn])){
            $_SESSION['cart'][$book_isbn] = 1;  // Add new book with quantity 1
        } elseif(isset($_POST['cart'])){
            $_SESSION['cart'][$book_isbn]++;  // Increment quantity if already in cart
        }
    }

    // Handle saving changes to the cart
    if(isset($_POST['save_change'])){
        foreach($_SESSION['cart'] as $isbn => $qty){
            $new_qty = intval($_POST[$isbn]);  // Convert the quantity to an integer
            if($new_qty == 0){
                unset($_SESSION['cart'][$isbn]);  // Remove book from cart if quantity is 0
            } else {
                $_SESSION['cart'][$isbn] = $new_qty;  // Update the quantity
            }
        }
    }

    // Set the page title and include the header
    $title = "Your shopping cart";
    require "./template/header.php";
?>

<h4 class="fw-bolder text-center">Cart List</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<?php
    // Check if the cart is not empty
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
        // Open the database connection once
        $conn = db_connect();

        // Calculate the total price and items
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
                        // Loop through cart items
                        foreach($_SESSION['cart'] as $isbn => $qty){
                            $book = getBookByIsbn($conn, $isbn); // Fetch book details by ISBN
                    ?>
                    <tr>
                        <td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
                        <td><?php echo "$" . number_format($book['book_price'], 2); ?></td>
                        <td><input type="text" value="<?php echo $qty; ?>" size="2" name="<?php echo $isbn; ?>"></td>
                        <td><?php echo "$" . number_format($qty * $book['book_price'], 2); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th><?php echo $_SESSION['total_items']; ?></th>
                        <th><?php echo "$" . number_format($_SESSION['total_price'], 2); ?></th>
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
        // Close the database connection
        mysqli_close($conn);
    } else {
?>
    <div class="alert alert-warning rounded-0">Your cart is empty! Please add at least 1 book to purchase first.</div>
<?php
    }
    require_once "./template/footer.php";
?>
