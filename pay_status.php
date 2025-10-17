<?php
require_once 'inc/stripe_config.php'; 
require_once 'admin/inc/db_config.php';
require_once 'inc/links.php';

$status = $_GET['status'] ?? 'failure';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        .alert-box {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-fail {
            background-color: #eed3cfff;
            color: #791616ff;
        }

        a {
            text-decoration: none;
            color: #0d6efd;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php

require_once 'inc/header.php';
?>

<div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">PAYMENT STATUS</h2>    
</div>

<div class="container">
    <?php if ($status === 'success'): ?>
        <div class="alert-box alert-success">
            <h3 class="success"><i class="bi bi-check-circle-fill"></i> Payment done</h3>
            <p>Your booking successful.</p>
            <a href="bookings.php" class="btn">Go to My Bookings</a>
        </div>            
        
    <?php else: ?>
        <div class="alert-box alert-fail">
            <h3 class="failure"><i class="bi bi-exclamation-triangle-fill"></i> Payment Failed</h3>
            <p>Your booking has been declined by your bank. Please try again or use a different method to complete the payment.</p>
            <a href="packages.php" class="btn">Back to Packages</a>
        </div>
        
    <?php endif; ?>  
</div>

<?php require_once 'inc/footer.php'; ?>
</body>
</html>
