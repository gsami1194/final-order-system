<?php
header('Content-Type: application/json');
require_once '../config/db.php';
$order_id = $_GET['order_id'] ?? 0;
if (!$order_id) { echo json_encode(['error' => 'Invalid order ID']); exit; }
$order_result = $conn->query("SELECT * FROM orders WHERE id = $order_id");
$order = $order_result->fetch_assoc();
if (!$order) { echo json_encode(['error' => 'Order not found']); exit; }
$items_result = $conn->query("SELECT p.name, oi.quantity, oi.unit_price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE order_id = $order_id");
$items = [];
while ($row = $items_result->fetch_assoc()) { $items[] = $row; }
$payment_result = $conn->query("SELECT request_payload, response_payload FROM payments WHERE order_id = $order_id");
$payment = $payment_result->fetch_assoc();
$refunds_result = $conn->query("SELECT request_payload, response_payload FROM refunds WHERE payment_id IN (SELECT id FROM payments WHERE order_id = $order_id)");
$refunds = [];
while ($row = $refunds_result->fetch_assoc()) {
    $refunds[] = ['request' => $row['request_payload'], 'response' => $row['response_payload']];
}
$response = [
    'id' => $order['id'],
    'status' => $order['status'],
    'customer_name' => $order['customer_name'],
    'email' => $order['email'],
    'items' => $items,
    'payment_request' => $payment['request_payload'] ?? '',
    'payment_response' => $payment['response_payload'] ?? '',
    'refunds' => $refunds
];
echo json_encode($response);
