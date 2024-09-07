<?php
session_start();

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
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user data from the Customer table
    $query = "SELECT * FROM Customer WHERE Email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password
    if ($user && password_verify($password, $user['Password'])) {
        // Set session variables
        $_SESSION['CustomerID'] = $user['CustomerID'];  // Set CustomerID in session
        $_SESSION['user_name'] = $user['Name'];  // Assuming there is a Name field in the Customer table

        // Redirect to index.php or order page
        header("Location: index.php");
        exit();
    } else {
        // Redirect back to login page with error
        header("Location: login.html?error=invalid_credentials");
        exit();
    }
}
