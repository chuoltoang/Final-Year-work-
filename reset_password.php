<?php
$token = $_GET['token'] ?? '';

if (!$token) {
    die('Invalid request.');
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'onlinefoodordering';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (!$con) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$stmt = $con->prepare('SELECT CustomerID FROM customer WHERE reset_token = ? AND reset_expires > ?');
$current_time = time();
$stmt->bind_param('si', $token, $current_time);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die('Invalid or expired token.');
}

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <form action="update_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
