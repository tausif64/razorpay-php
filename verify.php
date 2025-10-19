<?php
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

header('Content-Type: application/json');

$keyId = "";
$keySecret = "";

$api = new Api($keyId, $keySecret);

// Collect post data
$payment_id = $_POST['razorpay_payment_id'] ?? null;
$order_id = $_POST['razorpay_order_id'] ?? null;
$signature = $_POST['razorpay_signature'] ?? null;

if ($payment_id && $order_id && $signature) {
    try {
        // Verify signature
        $attributes = [
            'razorpay_order_id' => $order_id,
            'razorpay_payment_id' => $payment_id,
            'razorpay_signature' => $signature
        ];

        $api->utility->verifyPaymentSignature($attributes);

        // You can also log it to a database here

        echo json_encode(['success' => true]);
    } catch (SignatureVerificationError $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Signature verification failed: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Missing required POST data'
    ]);
}
