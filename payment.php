<?php
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

header('Content-Type: application/json');

$keyId = "rzp_test_eSJNxTqrJwrb8I";
$keySecret = "nk5iWiHf3bebEidskb999njB";

$api = new Api($keyId, $keySecret);

if (isset($_POST['amount'])) {
    $amount = preg_replace('/[^0-9.]/', '', $_POST['amount']);

    if (!is_numeric($amount) || $amount <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid amount']);
        exit;
    }

    $amountInPaise = round(floatval($amount) * 100);

    try {
        $orderData = [
            'receipt' => 'receipt_' . uniqid(),
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'payment_capture' => 1
        ];

        $razorpayOrder = $api->order->create($orderData);

        echo json_encode([
            'order_id' => $razorpayOrder['id'],
            'amount' => $amountInPaise,
            'currency' => 'INR'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Razorpay API error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Amount not provided']);
}
