<?php
require_once 'admin/inc/db_config.php';
require_once 'inc/links.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$order_id = $_POST['order_id'] ?? null;
$status   = $_POST['status'] ?? null;

if (!$order_id || !$status) {
    die("Invalid data");
}

if ($status === 'success') {
    // Fetch booking + package
    $q = $conn->prepare("SELECT bo.booking_id, bo.package_id, p.price 
                         FROM booking_order bo 
                         JOIN packages p ON bo.package_id = p.id 
                         WHERE bo.order_id = ? LIMIT 1");
    $q->bind_param("s", $order_id);
    $q->execute();
    $res = $q->get_result()->fetch_assoc();

    $booking_id = $res['booking_id'] ?? null;
    $amount     = $res['price'] ?? 0;

    $trans_id = 'TRANS' . time();
    $trans_resp_msg = 'TXN_SUCCESS';

    // Update booking_order
    $stmt = $conn->prepare("UPDATE booking_order 
        SET trans_id=?, trans_amt=?, trans_status='completed', booking_status='booked', trans_resp_msg=? 
        WHERE order_id=?");
    $stmt->bind_param("sdss", $trans_id, $amount, $trans_resp_msg, $order_id);
    $stmt->execute();

    // Update booking_details
    if ($booking_id) {
        $stmt2 = $conn->prepare("UPDATE booking_details SET price=?, total_pay=? WHERE booking_id=?");
        $stmt2->bind_param("ddi", $amount, $amount, $booking_id);
        $stmt2->execute();
    }

    header("Location: pay_status.php?status=success");
    exit;

} else {
    // Failure
    $trans_id = 'TRANS' . time();
    $trans_resp_msg = 'Your booking has been declined by your bank.';
    $stmt = $conn->prepare("UPDATE booking_order 
        SET trans_id=?, trans_status='failed', booking_status='payment failed', trans_resp_msg=?
        WHERE order_id=?");
    $stmt->bind_param("sss", $trans_id, $trans_resp_msg, $order_id);
    $stmt->execute();

    header("Location: pay_status.php?status=failure");
    exit;
}
