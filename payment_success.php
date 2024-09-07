<?php
$orderID = $_GET['order_id'];
echo "<h1>Payment Successful!</h1>";
echo "<p>Your payment for Order ID: $orderID has been completed successfully.</p>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .back-to-home {
    margin-bottom: 20px;
    text-align: right;
}

.home-btn {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

.home-btn:hover {
    background-color: #0056b3;
}
</style>
<body>
<div class="back-to-home">
            <a href="index.php" class="home-btn">Back to Home</a>
        </div>
</body>
</html>