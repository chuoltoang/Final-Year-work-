<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['CustomerID']);
$customerName = $isLoggedIn ? htmlspecialchars($_SESSION['user_name']) : "";

// Database connection
include('db_connection.php');

// Fetch food items from the database
$query = "SELECT * FROM food";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Food Ordering System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Your custom styles -->
    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Navigation Bar -->
   <!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#home">Maale Restaurant</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#menu">Menu</a></li>
            <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
            <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            <?php if ($isLoggedIn): ?>
                <li class="nav-item"><a class="nav-link" href="#">Welcome, <?php echo $customerName; ?></a></li>
                <li class="nav-item"><a class="nav-link" href="confirm_order_status.php">Order Status</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

    </nav>

 <!-- Hero Section -->
<section id="home" class="jumbotron jumbotron-fluid text-center" style="background-image: url('assets/images/hero-background.jpg'); background-size: cover; color: white;">
    <div class="container">
        <h1 class="display-4">Welcome to Maale Restaurant</h1>
        <p class="lead">Online Food Ordering System</p>
        <a href="#menu" class="btn btn-primary btn-lg">Order Now</a>
    </div>
</section>


    <!-- Menu Section -->
    <section id="menu">
        <h2>Our Menu</h2>
        <div id="food-container">
        <?php while ($food = mysqli_fetch_assoc($result)): ?>
    <div class="food-item" data-food-id="<?php echo htmlspecialchars($food['FoodID']); ?>">
        <img src="<?php echo htmlspecialchars(str_replace('Admin/', '', $food['FoodPhoto'])); ?>" alt="<?php echo htmlspecialchars($food['Name']); ?>" class="food-photo">
        <h2 class="food-name"><?php echo htmlspecialchars($food['Name']); ?></h2>
        <p class="food-description"><?php echo htmlspecialchars($food['Description']); ?></p>
        <p class="food-price" data-unit-price="<?php echo htmlspecialchars($food['Price']); ?>">
            $<?php echo number_format($food['Price'], 2); ?>
        </p>
        <button class="order-button">Order Now</button>
    </div>
<?php endwhile; ?>


        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <h2>About Us</h2>
        <p>
            Maale Restaurant has been serving delicious and authentic dishes for over a decade. 
            Our mission is to provide our customers with a delightful dining experience, whether they dine in or order online.
            We pride ourselves on using the freshest ingredients to create meals that satisfy both the stomach and the soul.
        </p>
        <p>
            Our menu offers a wide variety of options, catering to different tastes and dietary preferences.
            From traditional favorites to innovative new dishes, there's something for everyone at Maale Restaurant.
            We are committed to delivering not just food, but an experience, with every order.
        </p>
        <p>
            Thank you for choosing Maale Restaurant. We look forward to serving you again and again!
        </p>
    </section>

    <!-- Order Summary Section -->
    <section id="order-summary" style="display:none;">
        <h2>Order Summary</h2>
        <div id="order-details">
            <ul id="order-items">
                <!-- List of ordered items dynamically inserted here -->
            </ul>
        </div>

        <!-- Subtotal -->
        <div class="order-summary-detail">
            <span>Subtotal:</span>
            <span id="subtotal-amount">$0.00</span>
        </div>

        <!-- Tax -->
        <div class="order-summary-detail">
            <span>Tax (10%):</span>
            <span id="tax-amount">$0.00</span>
        </div>

        <!-- Delivery Charges -->
        <div class="order-summary-detail">
            <span>Delivery Charges:</span>
            <span id="delivery-amount">$5.00</span> <!-- Set to default $5.00 -->
        </div>

        <!-- Total Amount -->
        <div class="order-summary-detail">
            <strong>Total:</strong>
            <strong id="total-display">$0.00</strong>
        </div>

        <!-- Order Form -->
        <form action="process_order.php" method="POST">
            <input type="hidden" name="order_items" id="order-items-data"> <!-- Dynamically populated -->
            <input type="hidden" name="total_amount" id="total_amount"> <!-- Dynamically populated -->
            <input type="hidden" name="customer_id" value="<?php echo $isLoggedIn ? $_SESSION['CustomerID'] : ''; ?>"> <!-- Fetch CustomerID from session -->
            <button type="submit">Place Order</button>
        </form>

        <!-- Buttons for editing order and proceeding to payment -->
        <div class="order-actions">
            <button id="edit-order">Edit Order</button>
            <button id="proceed-to-payment" onclick="submitOrderAndProceed();">Proceed to Payment</button>
        </div>
    </section>

    <!-- Contact Section -->
<section id="contact" class="container my-5">
    <h2 class="text-center mb-4">Contact Us</h2>
    <form action="contact.php" method="POST">
        <div class="form-group">
            <input type="text" class="form-control" name="name" placeholder="Your Name" required>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Your Email" required>
        </div>
        <div class="form-group">
            <textarea name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
    </form>
</section>


    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Online Food Ordering System. All rights reserved.</p>
        <div class="social-media">
            <a href="#"><img src="assets/images/facebook-logo.png" alt="Facebook Logo">Facebook</a>
            <a href="#"><img src="assets/images/instagram-logo.png" alt="Instagram Logo">Instagram</a>
            <a href="#"><img src="assets/images/download.png" alt="Twitter Logo">Twitter</a>
        </div>
    </footer>

    <script src="assets/js/index.js"></script>
    <script>
        function submitOrderAndProceed() {
            const form = document.querySelector('form[action="process_order.php"]');
            form.submit();
        }
    </script>
</body>
</html>

    