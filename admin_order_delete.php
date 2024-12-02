<?php
    // Start session and include necessary files
    session_start();
    require_once "./functions/database_functions.php";
    $conn = db_connect();

    // Check if book ISBN is provided
    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
    } else {
        echo "Invalid request. No order found provided.";
        exit;
    }

    // Confirm deletion before proceeding
    if (isset($_GET['order_id'])) {
        // Prepare and execute delete query using a prepared statement to prevent SQL injection
        
        $sql1 = "DELETE FROM `order_items` WHERE `order_id` = '{$order_id}'";
        $sql2 = "DELETE FROM `orders` WHERE `orderid` = '{$order_id}'";
        $sql3 = "DELETE FROM `customers` WHERE `customerid` NOT IN (SELECT DISTINCT `customerid` FROM `orders`)";

        $order_result1 = mysqli_query($conn, $sql1);
        $order_result2 = mysqli_query($conn, $sql2);
        $order_result3 = mysqli_query($conn, $sql3);

        if ($order_result3) {
            // Redirect to admin book page with success message
            $_SESSION['order_message'] = "Order has been successfully deleted.";
            header("Location: admin_orderlist.php");
            exit;
        } else {
            // Show error message if deletion failed
            echo "Delete operation unsuccessful. " . mysqli_error($conn);
            exit;
        }
    }
echo 'test';
    // mysqli_stmt_close($stmt);
?>