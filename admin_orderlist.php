<?php
session_start();
require_once "./functions/admin.php";
$title = "List book";
require_once "./template/header.php";
require_once "./functions/database_functions.php";
require_once "./search_ALG/BST_admin.php";

// Establish database connection
$conn = db_connect();

// Fetch all books using the updated getAll() function
$results = getOrderList($conn);

// debug($results);

// Pagination
$booksPerPage = 5;
$totalBooks = count($results);
$totalPages = ceil($totalBooks / $booksPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startIndex = ($currentPage - 1) * $booksPerPage;
$paginatedResults = array_slice($results, $startIndex, $booksPerPage);

// debug($paginatedResults, 1);
?>

<h4 class="fw-bolder text-center">Customer Orders</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<?php if(isset($_SESSION['order_message'])): ?>
    <div class="alert alert-success rounded-0">
        <?= $_SESSION['order_message'] ?>
    </div>
<?php 
    unset($_SESSION['order_message']);
endif;
?>

<div class="card rounded-0">
    <div class="card-body">
        <div class="container-fluid">

            <table class="table table-striped table-bordered">
                <!-- <colgroup>
                    <col width="15%">
                    <col width="15%">
                    <col width="10%">
                    <col width="10%">
                    <col width="15%">
                    <col width="10%">
                    <col width="15%">
                    <col width="10%">
                </colgroup> -->
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Total Price</th>
                        <th>Payment Method</th>
                        <th>Status </th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paginatedResults as $row): 
                        // debug($row, 1);
                    
                        ?>
                        <tr>
                            <td class="px-2 py-1 align-middle"><?php echo $row['customer_name']; ?></td>
                            <td class="px-2 py-1 align-middle"><?php echo $row['customer_contact']; ?></td>
                            <td class="px-2 py-1 align-middle"><?php echo $row['customer_address']; ?></td>
                            <td class="px-2 py-1 align-middle"><?php echo $row['total_price']; ?></td>
                            <td class="px-2 py-1 align-middle"><?php echo $row['payment_method']; ?></td> 
                            <td class="px-2 py-1 align-middle">

                            <?php

                                    $selectedStatus = isset($row['status']) ? $row['status'] : 0; // Default to 0 (Pending)

                                    // Determine the status text based on the selected value
                                    if ($selectedStatus == 0) {
                                        $statusText = "<p style='color:#d35400;'> Pending </p>";
                                    } elseif ($selectedStatus == 1) {
                                        $statusText = "<p style='color:#9b59b6;'> Processing </p>";
                                    } elseif ($selectedStatus == 2) {
                                        $statusText = "<p style='color:#30cb83;'> Completed </p>";
                                    } elseif ($selectedStatus == 3) {
                                        $statusText = "<p style='color:red;'> Canceled </p>";
                                    } elseif ($selectedStatus == 4) {
                                        $statusText = "<p style='color:red;'> Returned </p>";
                                    } elseif ($selectedStatus == 5) {
                                        $statusText = "<p style='color:#54a0ff;'> Refunded </p>";
                                    } else {
                                        $statusText = "Unknown status.";
                                    }
                            ?>
                            <?php echo $statusText; ?></td>
                            <td class="px-2 py-1 align-middle"><?php echo $row['remarks']; ?></td>
                           
                            <td class="px-2 py-1 align-middle text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="admin_order_update.php?order_id=<?php echo $row['orderid']; ?>" class="btn btn-sm rounded-0 btn-primary" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="admin_order_delete.php?order_id=<?php echo $row['orderid']; ?>" class="btn btn-sm rounded-0 btn-danger" title="Delete" onclick="if(confirm('Are you sure to delete this book?') === false) event.preventDefault()">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pagination">
            <?php
                // Display pagination links
                if($totalPages > 1){
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == $currentPage) {
                            echo "<a href=\"?page={$i}\" class=\"active\">{$i}</a>";
                        } else {
                            echo "<a href=\"?page={$i}\">{$i}</a>";
                        }
                    }
                }
                
            ?>
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