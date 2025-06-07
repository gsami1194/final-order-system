<?php
header('Content-Type: application/json');
require_once '../config/db.php';
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || empty($data['items']) || empty($data['customer_name']) || empty($data['email'])) {
    echo json_encode(['message' => 'Invalid input']);
    exit;
}
$customer_name = $data['customer_name'];
$email = $data['email'];
$shipping_method = $data['shipping_method'] ?? 'shipping';
$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, email, shipping_method, status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("sss", $customer_name, $email, $shipping_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
    foreach ($data['items'] as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $result = $conn->query("SELECT price FROM products WHERE id = $product_id");
        if ($row = $result->fetch_assoc()) {
            $unit_price = $row['price'];
            $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $unit_price);
            $stmt_item->execute();
        }
    }
    $conn->commit();
    echo json_encode(['message' => 'Order created successfully', 'order_id' => $order_id]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
}
