<?php
    /*
        Calculate the total price of items in the cart.
        - Loops through the array of $_SESSION['cart'][book_isbn] => quantity.
        - For each ISBN, fetch the price from the database.
        - Multiply the price by the quantity and sum it up.
        - Returns the total price.
    */

    // Update the path to functions.php based on its location
    require_once '../functions.php'; // Adjust this path as needed

    function total_price($cart) {
        $price = 0.0; // Initialize total price as 0.0
        
        if (is_array($cart)) {
            foreach ($cart as $isbn => $qty) {
                // Call the getBookPrice function to fetch the price of the book
                $bookprice = getBookPrice($isbn); 

                if ($bookprice !== null) { // Ensure the price is valid
                    $price += $bookprice * $qty; // Add price * quantity to the total
                } else {
                    // Handle cases where price is not found (optional)
                    error_log("Price not found for ISBN: $isbn"); 
                }
            }
        }
        
        return $price; // Return the total price
    }

    /*
        Calculate the total number of items in the cart.
        - Loops through the array of $_SESSION['cart'][book_isbn] => quantity.
        - Sums up the quantities for all books.
        - Returns the total number of items.
    */
    function total_items($cart) {
        $items = 0; // Initialize total items as 0

        if (is_array($cart)) {
            foreach ($cart as $isbn => $qty) {
                $items += $qty; // Add the quantity of each book to the total
            }
        }
        
        return $items; // Return the total number of items
    }
?>
