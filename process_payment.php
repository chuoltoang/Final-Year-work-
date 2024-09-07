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

// Initialize variables and check POST data
$orderID = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$totalAmount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
$paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

// Ensure required POST data is provided
if (!$orderID || !$totalAmount || !$paymentMethod) {
    die("Order ID, total amount, and payment method are required.");
}

// Update payment details in database
$stmt = $con->prepare("INSERT INTO payment (OrderID, PaymentDate, Amount, PaymentMethod) VALUES (?, NOW(), ?, ?)");
$stmt->bind_param("ids", $orderID, $totalAmount, $paymentMethod);

if ($stmt->execute()) {
    // Update order status to 'Paid'
    $stmt = $con->prepare("UPDATE orderdetails SET Status = 'Paid' WHERE OrderID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    
    // Redirect to order status confirmation
    header("Location: confirm_order_status.php?order_id=" . urlencode($orderID));
    exit();
} else {
    echo "Error processing payment.";
}

$stmt->close();
$con->close();
?>
