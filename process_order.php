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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderItems = $_POST['order_items']; // JSON encoded array
    $totalAmount = $_POST['total_amount'];
    $customerID = $_SESSION['CustomerID'];
    
    // Insert order into orderdetails table
    $orderDate = date('Y-m-d H:i:s');
    $status = 'Pending';
    $stmt = $con->prepare("INSERT INTO orderdetails (CustomerID, OrderDate, Status, TotalAmount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $customerID, $orderDate, $status, $totalAmount);
    
    if ($stmt->execute()) {
        $orderID = $con->insert_id;

        // Insert order items into orderfood table
        $orderItems = json_decode($orderItems, true); // Decode JSON to array
        $stmt = $con->prepare("INSERT INTO orderfood (OrderID, FoodID, Quantity) VALUES (?, ?, ?)");
        foreach ($orderItems as $item) {
            $foodID = $item['foodId'];
            $quantity = $item['quantity'];
            $stmt->bind_param("iii", $orderID, $foodID, $quantity);
            $stmt->execute();
        }

        // Redirect to payment page
        header("Location: payment.php?order_id=" . urlencode($orderID));
        exit();
    } else {
        echo "Error processing order.";
    }
}

$con->close();
?>
