<?php
header('Content-Type: application/json');
require_once '../config/db.php';
$sql = "SELECT id, customer_name, status, created_at FROM orders ORDER BY created_at DESC";
$result = $conn->query($sql);
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
echo json_encode($orders);
