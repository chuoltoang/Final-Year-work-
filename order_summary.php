<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['CustomerID'])) {
    header("Location: login.html");
    exit();
}

// Example order items data stored in session (you'll replace this with actual data)
$_SESSION['order_items'] = [
    ['name' => 'Pizza', 'quantity' => 2, 'price' => 12.99, 'subtotal' => 25.98],
    ['name' => 'Burger', 'quantity' => 1, 'price' => 8.99, 'subtotal' => 8.99],
    // Add more items as needed
];

// Include the HTML and JavaScript for the order summary
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="order_summary.css">
    <script src="assets/js/order_summary.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="order-card">
            <h2 class="order-title">Order Summary</h2>
            <ul class="order-items">
                <?php
                $totalAmount = 0;
                if (isset($_SESSION['order_items'])) {
                    foreach ($_SESSION['order_items'] as $item) {
                        echo "<li class='order-item'>
                                <span class='item-name'>{$item['name']}</span>
                                <span class='item-quantity'>{$item['quantity']}</span>
                                <span class='item-subtotal'>\${$item['subtotal']}</span>
                              </li>";
                        $totalAmount += $item['subtotal'];
                    }
                }
                ?>
            </ul>
            <div class="total-container">
                <p class="total-label">Total Amount:</p>
                <p class="total-amount">$<span id="total-display"><?php echo number_format($totalAmount, 2); ?></span></p>
            </div>
            <form id="order-summary-form" action="process_payment.php" method="post">
                <input type="hidden" name="total_amount" id="total_amount" value="<?php echo number_format($totalAmount, 2); ?>">
                <input type="hidden" name="payment_method" id="payment_method" value="cod">
                <button type="submit" class="btn-proceed">Proceed to Payment</button>
            </form>
        </div>
    </div>
</body>
</html>
