<?php
    // Debugging: Print current directory and resolved path
    echo "Current working directory: " . getcwd() . "<br>";
    echo "Resolved path: " . realpath('../functions.php') . "<br>";

    // Update the path to functions.php based on its actual location
    require_once '/var/www/html/functions.php'; // Use absolute path as a fallback

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
