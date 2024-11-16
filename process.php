<div class="form-group mb-3">
    <div class="mb-3">
        <!-- Payment Method Selection -->
        <div class="mb-3">
            <label for="payment" class="control-label">Payment Method</label>
            <select name="payment" class="form-control rounded-0" id="payment" onchange="checkPayment()">
                <option value="cod">Cash on Delivery (COD)</option>
                <option value="khalti">Khalti</option>
            </select>
        </div>

        <!-- Message for unavailable payment method (Khalti) -->
        <div id="message" class="text-danger" style="display: none;">
            <p>This payment method is not currently available. Please choose Cash on Delivery.</p>
        </div>

        <!-- Purchase Button -->
        <button id="purchaseBtn" class="btn btn-primary" type="submit" name="purchaseBtn" disabled>Purchase</button>

        <div id="orderMessage" class="text-success" style="display: none;">
            <p>Your order has been placed, and you will get a call for delivery.</p>
        </div>

        <script>
        // Function to handle payment method selection
        function checkPayment() {
            var paymentMethod = document.getElementById("payment").value;
            var messageDiv = document.getElementById("message");
            var purchaseBtn = document.getElementById("purchaseBtn");

            if (paymentMethod === "cod") {
                messageDiv.style.display = "none";  // Hide error message
                purchaseBtn.disabled = false;  // Enable the purchase button
            } else if (paymentMethod === "khalti") {
                messageDiv.style.display = "block";  // Show the unavailable message
                purchaseBtn.disabled = true;  // Disable the purchase button
            }
        }

        // Listen for form submission and show order confirmation
        var purchaseForm = document.querySelector("form");
        purchaseForm.addEventListener("submit", function(e) {
            e.preventDefault();  // Prevent form from submitting immediately
            placeOrder();  // Show order confirmation message
            this.submit();  // Submit the form after showing the message
        });

        </script>
    </div>
</div>
