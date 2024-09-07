<?php
// Load Composer's autoloader if using Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

include('db_connection.php');
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'chuoltoang1@gmail.com';               // SMTP username
        $mail->Password   = 'sran lbwu itqp kivs';                        // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('maalerestaurant@gmail.com');          // Add the restaurant's email address
        
        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'New Contact Us Message';
        $mail->Body    = "<h3>You have received a new message from $name</h3>
                          <p><strong>Email:</strong> $email</p>
                          <p><strong>Message:</strong><br>$message</p>";
        $mail->AltBody = "You have received a new message from $name\n\nEmail: $email\n\nMessage:\n$message";

        // Send the email
        if ($mail->send()) {
            echo 'Message sent successfully!';
        } else {
            echo 'Failed to send message.';
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
