<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['CustomerID'])) {
    header("Location: login.html");
    exit();
}

// Database connection details
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'onlinefoodordering';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

// Check connection
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Initialize variables
$orderID = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$totalAmount = 0;

// Ensure order ID is provided
if (!$orderID) {
    die("Order ID is required.");
}

// Fetch order details
$stmt = $con->prepare("SELECT * FROM orderdetails WHERE OrderID = ?");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Check if order exists
if (!$order) {
    die("Order not found.");
}

$totalAmount = $order['TotalAmount'];

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="assets/css/payment.css">
</head>
<body>
    <h2>Payment</h2>
    <p>Order ID: <?php echo htmlspecialchars($orderID); ?></p>
    <p>Total Amount: $<?php echo number_format($totalAmount, 2); ?></p>

    <!-- Payment form -->
    <form action="process_payment.php" method="post">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderID); ?>">
        <input type="hidden" name="total_amount" value="<?php echo number_format($totalAmount, 2); ?>">
        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method">
            <option value="cod">Cash on Delivery</option>
        </select>
        <button type="submit">Complete Payment</button>
    </form>
</body>
</html>
