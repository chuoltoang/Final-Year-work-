<?php
// Include PHPMailer class files
require 'vendor/autoload.php'; // Ensure you have this file in your project

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Database connection
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'onlinefoodordering';

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (!$con) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    // Check if the email exists in the database
    $stmt = $con->prepare('SELECT CustomerID FROM customer WHERE Email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $expires = time() + 3600; // 1 hour expiration

        // Store the token and expiration in the database
        $stmt = $con->prepare('UPDATE customer SET reset_token = ?, reset_expires = ? WHERE Email = ?');
        $stmt->bind_param('sis', $token, $expires, $email);
        $stmt->execute();

        // Send the reset email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'chuoltoang1@gmail.com';
            $mail->Password = 'sran lbwu itqp kivs'; // Update with your email password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('no-reply@yourdomain.com', 'MAALE RESTAURANT');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "<p>To reset your password, please click the link below:</p>
                           <p><a href='http://yourdomain.com/reset_password.php?token=$token'>Reset Password</a></p>";
            $mail->AltBody = "To reset your password, please click the link below:
                               http://yourdomain.com/reset_password.php?token=$token";

            $mail->send();
            echo 'A password reset link has been sent to your email address.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'No account found with that email address.';
    }

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <form action="forgot_password.php" method="post">
        <h2>Forgot Your Password?</h2>
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
</html>
