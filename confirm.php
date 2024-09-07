<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'onlinefoodordering';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Hash the password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Auto-generate CustomerID and Confirmation Code
    $customer_id = uniqid();
    $confirmation_code = rand(100000, 999999);

    // Save customer details with confirmation code
    $stmt = $con->prepare('INSERT INTO customer (CustomerID, Name, Email, Phone, Address, Password, ConfirmationCode) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssss', $customer_id, $name, $email, $phone, $address, $hashed_password, $confirmation_code);
    $stmt->execute();

    // Send confirmation email
    $subject = "Email Confirmation";
    $message = "Your confirmation code is: $confirmation_code";
    $headers = "From: no-reply@yourdomain.com\r\n";

    if (mail($email, $subject, $message, $headers)) {
        echo 'A confirmation email has been sent. Please check your email.';
    } else {
        echo 'Failed to send confirmation email.';
    }
}

$con->close();
?>
