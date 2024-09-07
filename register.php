<?php
session_start();

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to the Composer autoload file

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'onlinefoodordering';

// Establish the database connection
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

// Check for connection errors
if (!$con) {
    $error = mysqli_connect_error();
    die("Failed to connect to MySQL: $error (Error code: " . mysqli_connect_errno() . ")");
}

if (isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Hash the password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Auto-generate Confirmation Code
    $confirmation_code = rand(100000, 999999); // Generate a 6-digit confirmation code

    // Prepare the SQL statement to insert the customer
    $stmt = $con->prepare('INSERT INTO customer (Name, Email, Phone, Address, Password, ConfirmationCode) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssss', $name, $email, $phone, $address, $hashed_password, $confirmation_code);

    if ($stmt->execute()) {
        // Send confirmation email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();                                    // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                             // Enable SMTP authentication
            $mail->Username = 'chuoltoang1@gmail.com';          // SMTP username
            $mail->Password = 'sran lbwu itqp kivs';            // SMTP password (Use an app-specific password if 2FA is enabled)
            $mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                  // TCP port to connect to

            // Recipients
            $mail->setFrom('no-reply@ychuoltoang.com', 'MAALE RESTAURANT');
            $mail->addAddress($email, $name);                   // Add a recipient

            // Content
            $mail->isHTML(true);                                // Set email format to HTML
            $mail->Subject = 'Email Confirmation';
            $mail->Body    = "<p>Your confirmation code is: <strong>$confirmation_code</strong></p>";
            $mail->AltBody = "Your confirmation code is: $confirmation_code"; // Plain text alternative

            $mail->send();
            echo '<p>Account created successfully! A confirmation email has been sent. Please check your email.</p>';
            echo '<script>setTimeout(function() { window.location.href = "index.php"; }, 3000);</script>'; // Redirect to home page after 3 seconds
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: Could not execute the statement.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$con->close();
?>
