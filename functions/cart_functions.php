<?php
// Debugging: Verify the current working directory
echo "Current working directory: " . getcwd() . "<br>";

// Debugging: Verify if the file exists
if (file_exists(__DIR__ . '/../functions.php')) {
    echo "functions.php exists.<br>";
} else {
    echo "functions.php does not exist.<br>";
    exit; // Stop execution if the file is missing
}

// Include the functions.php file
require_once __DIR__ . '/../functions.php';

// Function to calculate the total price of items in the cart
function total_price($cart) {
    $price = 0.0; // Initialize total price
    
    if (is_array($cart)) {
        foreach ($cart as $isbn => $qty) {
            $bookprice = getBookPrice($isbn); // Call getBookPrice from functions.php
            if ($bookprice !== null) {
                $price += $bookprice * $qty; // Add price * quantity to the total
            } else {
                error_log("Price not found for ISBN: $isbn"); // Log error if price not found
            }
        }
    }
    
    return $price;
}

// Function to calculate total number of items in the cart
function total_items($cart) {
    $items = 0; // Initialize total items

    if (is_array($cart)) {
        foreach ($cart as $isbn => $qty) {
            $items += $qty; // Add the quantity of each book to the total
        }
    }
    
    return $items;
}
?>
