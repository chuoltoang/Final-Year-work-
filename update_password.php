<?php
session_start();

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if (!$token || !$password) {
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

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $con->prepare('UPDATE customer SET Password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?');
$stmt->bind_param('ss', $hashed_password, $token);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo 'Your password has been updated successfully. You can now <a href="login.html">login</a>.';
} else {
    echo 'Failed to update password. Please try again.';
}

$stmt->close();
$con->close();
?>
