<?php
    ob_start(); // Start output buffering
    session_start();
    require_once "./functions/admin.php";
    $title = "Update Order Status";
    require_once realpath('template/header.php');
    require_once "./functions/database_functions.php";
    require_once "./functions/helper_function.php";

    $conn = db_connect();

    // debug($_GET, 1);
    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
    } else {
        echo "Empty query!";
        exit;
    }

    if (!isset($order_id)) {
        echo "Empty isbn! Check again!";
        exit;
    }

    // Get order data by id
    $rowData = getOrderById($conn, $order_id);

    // debug($rowData);
    if (!$rowData) {
        $_SESSION['order_message'] = "Order Details does not exist!";
        redirect("admin_orderlist.php");
    }

    $selectedStatus = isset($rowData['status']) ? $rowData['status'] : 0; // Default to 0 (Pending)

    // Determine the status text based on the selected value
    if ($selectedStatus == 0) {
        $statusText = "Pending - the order is open but awaiting processing.";
    } elseif ($selectedStatus == 1) {
        $statusText = "Processing - the order is being processed.";
    } elseif ($selectedStatus == 2) {
        $statusText = "Completed - the order has been completed.";
    } elseif ($selectedStatus == 3) {
        $statusText = "Canceled - the order has been cancelled.";
    } elseif ($selectedStatus == 4) {
        $statusText = "Returned - the order has been returned.";
    } elseif ($selectedStatus == 5) {
        $statusText = "Refunded - the order has been refunded.";
    } else {
        $statusText = "Unknown status.";
    }

    // print_r($_POST);

    // debug($rowData);
    if (isset($_POST['edit'])) {
        // debug($_POST, 1);
        // Input validation
        if (empty($_POST['name']) || empty($_POST['address']) || empty($_POST['contact']) ) {
            $err = "All fields are required!";
        } else {
            
            // //for customer
            $customer_id = trim($_POST['customer_id']);
            $customer_data = "";
            foreach ($_POST as $k => $v) {
                if (!in_array($k, ['edit', 'book_id','book_title','customer_id','total_price','payment_method','status','remarks'])) {
                    if (!empty($customer_data)) $customer_data .= ", ";
                    $customer_data .= "`{$k}` = '" . (mysqli_real_escape_string($conn, $v)) . "'";
                }
            }

            $customer_query = "UPDATE customers SET $customer_data WHERE customerid = '{$customer_id}'";
            $customer_result = mysqli_query($conn, $customer_query);

            //for order
            $order_id = trim($_GET['order_id']);
            $order_data = "";
            foreach ($_POST as $k => $v) {
                if (!in_array($k, ['edit', 'book_id','book_title','customer_id','total_price','name','address','contact'])) {
                    if (!empty($order_data)) $order_data .= ", ";
                    $order_data .= "`{$k}` = '" . (mysqli_real_escape_string($conn, $v)) . "'";
                }
            }

            $order_query = "UPDATE orders SET $order_data WHERE orderid = '{$order_id}'";

            // debug($order_query, 1);
            $order_result = mysqli_query($conn, $order_query);

            if ($order_result) {
                $_SESSION['order_success'] = "Order Details have been updated successfully";
                redirect("admin_orderlist.php");
                exit; // Ensure no further code is executed after the redirect
            } else {
                $err = "Can't update data " . mysqli_error($conn);
            }
        }
    }

?>
<h4 class="fw-bolder text-center">Update Order Details</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<div class="row justify-content-center">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

<div class="card rounded-0 shadow">
    <div class="card-body">
        <div class="container-fluid">
                <table class="table">
                    <tr>
                    <th>ISBN</th>
                    <th>Publisher</th>   
                    <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    <?php

                        $orderedBookList = getAdminOrderBookList($conn, $order_id, $rowData['customer_id']);

                        foreach($orderedBookList as $key => $book){
                    ?>
                    <tr>
                        <td><?php echo $book['book_isbn']; ?></td>
                        <td><?php echo $book['publisher_name']; ?></td>
                        <td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
                        <td><?php echo "$" . $book['book_price']; ?></td>
                        <td><?php echo $book['quantity']; ?></td>
                        <td><?php echo "$" . $book['quantity'] * $book['book_price']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th colspan="5">&nbsp;</th>
                        <th><?php echo "$" . $rowData['total_price']; ?></th>
                    </tr>
                </table>
        </div>
    </div>
</div>


    </div>
    </div>

<div class="row justify-content-center">




    <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">


   
        <div class="card rounded-0 shadow">
            <div class="card-body">
                <div class="container-fluid">
                    <?php if (isset($err)): ?>
                        <div class="alert alert-danger rounded-0">
                            <?= $err ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="admin_order_update.php?order_id=<?php echo $rowData['orderid']; ?>">

                       <input type="hidden" name="customer_id" value="<?php echo $rowData['customer_id']; ?>" >

                        <div class="mb-3">
                            <label class="control-label">Customer Name</label>
                            <input class="form-control rounded-0" type="text" name="name" value="<?php echo $rowData['customer_name']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="control-label">Customer Address</label>
                            <input class="form-control rounded-0" type="text" name="address" value="<?php echo $rowData['customer_address']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="control-label">Customer Contact</label>
                            <input class="form-control rounded-0" type="text" name="contact" value="<?php echo $rowData['customer_contact']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="control-label">Payment Method</label>
                            <select  class="form-select rounded-0" required name="payment_method" id="payment_method">
                                <option value="cod" <?= $rowData['payment_method'] == 'cod' ? 'selected' : '' ?>>COD</option>
                                <option value="khalti" <?= $rowData['payment_method'] == 'khalti' ? 'selected' : '' ?>>Khalti</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="control-label">Status</label>
                            <select  class="form-select rounded-0" required name="status" id="status">
                                <option value="0" <?= $selectedStatus == 0 ? 'selected' : '' ?>>Pending</option>
                                <option value="1" <?= $selectedStatus == 1 ? 'selected' : '' ?>>Processing</option>
                                <option value="2" <?= $selectedStatus == 2 ? 'selected' : '' ?>>Completed</option>
                                <option value="3" <?= $selectedStatus == 3 ? 'selected' : '' ?>>Canceled</option>
                                <option value="4" <?= $selectedStatus == 4 ? 'selected' : '' ?>>Returned</option>
                                <option value="5" <?= $selectedStatus == 5 ? 'selected' : '' ?>>Refunded</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="control-label">Remarks</label>
                            <textarea class="form-control rounded-0" name="remarks" cols="40" rows="2"><?php echo $rowData['remarks']; ?></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="edit" class="btn btn-primary btn-sm rounded-0">Update</button>
                            <button type="reset" class="btn btn-default btn-sm rounded-0 border">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    if (isset($conn)) {
        mysqli_close($conn);
    }
    require_once "./template/footer.php";
?>
