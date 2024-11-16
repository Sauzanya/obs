<?php
session_start();
require_once "./functions/database_functions.php";
$title = "Checking out";
require "./template/header.php";

$exchange_rate = 130;
$_SESSION['total_items'] = 0;
$_SESSION['total_price'] = 0;

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate Name and Contact fields
    if (empty($_POST['name']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['name'])) {
        $errors[] = "Please enter a valid name (letters and spaces only).";
    }
    if (empty($_POST['Contact']) || !preg_match("/^[0-9]{10}$/", $_POST['Contact'])) {
        $errors[] = "Please enter a valid 10-digit contact number.";
    }

    if (empty($errors)) {
        // Proceed with purchase (redirect to purchase page)
        header("Location: purchase.php");
        exit();
    }
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    ?>
    <div class="card rounded-0 shadow mb-3">
        <div class="card-body">
            <table class="table">
                <tr><th>Item</th><th>Price (NPR)</th><th>Quantity</th><th>Total (NPR)</th></tr>
                <?php
                foreach ($_SESSION['cart'] as $isbn => $qty) {
                    $conn = db_connect();
                    $book = getBookByIsbn($conn, $isbn);
                    if ($book) {
                        $price_in_npr = $book['book_price'] * $exchange_rate;
                        $_SESSION['total_items'] += $qty;
                        $_SESSION['total_price'] += $qty * $price_in_npr;
                ?>
                <tr>
                    <td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
                    <td><?php echo "₹ " . number_format($price_in_npr, 2); ?></td>
                    <td><?php echo $qty; ?></td>
                    <td><?php echo "₹ " . number_format($qty * $price_in_npr, 2); ?></td>
                </tr>
                <?php
                    }
                }
                ?>
                <tr><th>&nbsp;</th><th>&nbsp;</th><th><?php echo $_SESSION['total_items']; ?></th><th><?php echo "₹ " . number_format($_SESSION['total_price'], 2); ?></th></tr>
            </table>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12">
            <div class="card rounded-0 shadow">
                <div class="card-header"><div class="card-title h6 fw-bold">Please Fill the following form</div></div>
                <div class="card-body">
                    <?php if (!empty($errors)) { echo "<p class='text-danger'>" . implode("<br>", $errors) . "</p>"; } ?>
                    <form method="post" action="checkout.php">
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="Contact">Contact</label>
                            <input type="text" name="Contact" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <input type="submit" value="Purchase" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
} else {
    echo "<p class='text-warning'>Your cart is empty! Please make sure you add some books in it!</p>";
}

require_once "./template/footer.php";
?>

<script>
// Client-side validation
document.querySelector('form').addEventListener('submit', function(event) {
    let name = document.querySelector('input[name="name"]').value.trim();
    let contact = document.querySelector('input[name="Contact"]').value.trim();
    let nameError = /^[a-zA-Z\s]+$/.test(name);
    let contactError = /^[0-9]{10}$/.test(contact);
    
    if (!nameError || !contactError) {
        event.preventDefault();
        alert("Please ensure all fields are valid!");
    }
});
</script>
