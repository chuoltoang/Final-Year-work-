<?php
// admin_home.php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['AdminID'])) {
    header("Location: admin_login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="admin_style.css"> <!-- Linking your custom CSS -->
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_orders.php">Manage Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_customers.php">Manage Customers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_manage_food.php">Manage Food</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_manage_payment.php">Manage Payment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_reports.html">Reports</a>
                    </li>
                </ul>
                <form class="d-flex" action="admin_logout.php" method="POST">
                    <button class="btn btn-outline-light" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4">Welcome, <?php echo $_SESSION['AdminName']; ?>!</h1>
                <p class="lead">This is the admin dashboard where you can manage orders, customers, food, payments, and view reports.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> 

    <!-- Custom JavaScript -->
    <script src="admin_script.js"></script> <!-- Linking your custom JS -->
</body>
</html>
