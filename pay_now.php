<?php
require_once 'inc/stripe_config.php'; 
require_once 'admin/inc/db_config.php'; 
require_once 'inc/links.php';          

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate session and POST
if (!isset($_SESSION['package']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: packages.php");
    exit;
}

$package = $_SESSION['package'];
$user_id = $_SESSION['uId'] ?? 0;

$name     = trim($_POST['name'] ?? '');
$phonenum = trim($_POST['phonenum'] ?? '');
$address  = trim($_POST['address'] ?? '');
$arrival  = trim($_POST['arrival'] ?? '');
$leaving  = trim($_POST['leaving'] ?? '');

$order_id = 'ORD' . time() . rand(100,999);

// Insert booking order (pending)
$stmt = $conn->prepare("INSERT INTO booking_order 
    (user_id, package_id, arrival_date, leaving_date, refund, booking_status, order_id, trans_amt, trans_status, datentime)
    VALUES (?, ?, ?, ?, 0, 'pending', ?, 0, 'pending', NOW())");
$stmt->bind_param("iisss", $user_id, $package['id'], $arrival, $leaving, $order_id);
$stmt->execute();

$booking_id = $conn->insert_id;

// Insert booking details
$stmt2 = $conn->prepare("INSERT INTO booking_details 
    (booking_id, package_name, price, total_pay, user_name, phonenum, address)
    VALUES (?, ?, ?, 0, ?, ?, ?)");
$stmt2->bind_param("isssss", $booking_id, $package['name'], $package['price'], $name, $phonenum, $address);
$stmt2->execute();

// Create Stripe Checkout Session
$amount = (int) round($package['price'] * 100); // convert to cents

$success_url = SITE_URL . "stripe_response.php?session_id={CHECKOUT_SESSION_ID}&order_id=" . urlencode($order_id);
$cancel_url  = SITE_URL . "packages.php";

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => ['name' => $package['name']],
            'unit_amount' => $amount,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => $success_url,
    'cancel_url'  => $cancel_url,
]);

// Redirect to Stripe Checkout
header("Location: " . $session->url);
exit;
