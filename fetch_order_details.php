<?php
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

$orderID = $_GET['order_id'];
if (!$orderID) {
    echo json_encode(['error' => 'Order ID is required.']);
    exit();
}

$stmt = $con->prepare("SELECT OrderID, TotalAmount FROM orderdetails WHERE OrderID = ?");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode($result);

$stmt->close();
$con->close();
?>
