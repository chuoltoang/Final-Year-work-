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

// Get orders for the logged-in customer
$customerID = $_SESSION['CustomerID'];
$stmt = $con->prepare("SELECT OrderID, Status, OrderDate, TotalAmount FROM orderdetails WHERE CustomerID = ?");
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <link rel="stylesheet" href="assets/css/order_status.css">
</head>
<body>
    <div class="container">
        <h1>Your Order Status</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['OrderID']); ?></td>
                            <td><?php echo htmlspecialchars($order['Status']); ?></td>
                            <td><?php echo htmlspecialchars($order['OrderDate']); ?></td>
                            <td><?php echo number_format($order['TotalAmount'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no orders yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
