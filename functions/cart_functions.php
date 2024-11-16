<?php
// Function to calculate total price
function total_price($cart){
    $price = 0.0;
    if(is_array($cart)){
        foreach($cart as $isbn => $qty){
            $bookprice = getbookprice($isbn);  // Assuming you have this function to get book price
            if($bookprice){
                $price += $bookprice * $qty;
            }
        }
    }
    return $price;
}

// Function to calculate total items (books) in cart
function total_items($cart){
    $items = 0;
    if(is_array($cart)){
        foreach($cart as $isbn => $qty){
            $items += $qty;
        }
    }
    return $items;
}

// Placeholder for getbookprice function (implement it to fetch price from your database)
function getbookprice($isbn){
    // Database code here to fetch price based on ISBN
    return 10.00;  // Example fixed price for testing
}
?>
