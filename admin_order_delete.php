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
    if (isset($_POST['confirm_delete'])) {
        // Prepare and execute delete query using a prepared statement to prevent SQL injection
        $query = "DELETE FROM orders WHERE order_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $order_id);

        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Redirect to admin book page with success message
            $_SESSION['book_success'] = "Order has been successfully deleted.";
            header("Location: admin_orderlist.php");
            exit;
        } else {
            // Show error message if deletion failed
            echo "Delete operation unsuccessful. " . mysqli_error($conn);
            exit;
        }
    }

    mysqli_stmt_close($stmt);
?>