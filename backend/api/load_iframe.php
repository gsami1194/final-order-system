<?php
require_once '../config/db.php';
require_once '../includes/paytabs_config.php';
header('Content-Type: text/html');
$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'] ?? 0;
if (!$order_id) { echo "Invalid order ID."; exit; }
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();
if (!$order) { echo "Order not found."; exit; }
$items = $conn->query("SELECT oi.quantity, p.name, oi.unit_price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE order_id = $order_id");
$product_list = [];
$total = 0;
while ($row = $items->fetch_assoc()) {
    $product_list[] = ["name" => $row['name'], "quantity" => $row['quantity'], "unit_price" => $row['unit_price']];
    $total += $row['quantity'] * $row['unit_price'];
}
$request = [
    "profile_id" => $profile_id,
    "tran_type" => "sale",
    "tran_class" => "ecom",
    "cart_id" => "order_{$order_id}",
    "cart_description" => "Order #{$order_id}",
    "cart_currency" => "EGP",
    "cart_amount" => number_format($total, 2, '.', ''),
    "customer_details" => [
        "name" => $order['customer_name'],
        "email" => $order['email'],
        "phone" => "0000000000",
        "street1" => "NA",
        "city" => "Cairo",
        "state" => "Cairo",
        "country" => "EG",
        "zip" => "00000"
    ],
    "shipping_address" => [
        "name" => $order['customer_name'],
        "email" => $order['email'],
        "phone" => "0000000000",
        "street1" => "NA",
        "city" => "Cairo",
        "state" => "Cairo",
        "country" => "EG",
        "zip" => "00000"
    ],
    "callback" => "http://localhost:8000/success.php",
    "return" => "http://localhost:8000/success.php",
    "hide_shipping" => true,
    "show_billing" => false
];
$ch = curl_init("https://secure-eg.paytabs.com/payment/request");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "authorization: $server_key",
    "content-type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);
if (isset($result['redirect_url'])) {
    echo "<iframe src='{$result['redirect_url']}' width='100%' height='600' frameborder='0'></iframe>";
} else {
    echo "Payment initiation failed.";
}
