<?php
require_once 'inc/stripe_config.php'; 
require_once 'admin/inc/db_config.php';
require_once 'inc/links.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['session_id']) || !isset($_GET['order_id'])) {
    die("Invalid request");
}

$session_id = $_GET['session_id'];
$order_id   = $_GET['order_id'];

// Get Stripe session for display only
try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    $payment_intent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
    $amount = number_format($payment_intent->amount / 100, 2);
} catch (Exception $e) {
    $amount = "0.00";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Response</title>
<style>
    body { 
        font-family: Arial, sans-serif; 
        background:#f8f9fa; 
    }
    .card { 
        background:white; 
        width:450px; 
        margin:80px auto; 
        padding:30px; 
        border-radius:10px; 
        box-shadow:0 4px 12px rgba(0,0,0,0.08); 
        text-align:center; 
    }
    button { 
        padding:12px 18px; 
        border:none; 
        border-radius:6px; 
        margin:10px; 
        cursor:pointer; 
        font-size:16px; 
        color:#fff; 
    }
    .success { 
        background-color:#28a745; 
    }
    .failure { 
        background-color:#dc3545; 
    }
</style>
</head>
<body>

<div class="card">
    <h2>Payment Confirmation</h2>
    <p><strong>Order ID:</strong> <?= htmlspecialchars($order_id) ?></p>
    <p><strong>Amount:</strong> $<?= htmlspecialchars($amount) ?></p>
    <p>Select the final payment result:</p>

    <form action="update_payment.php" method="POST">
        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
        <button type="submit" name="status" value="success" class="success">Payment Successful</button>
        <button type="submit" name="status" value="failure" class="failure">Payment Failed</button>
    </form>
</div>

</body>
</html>
