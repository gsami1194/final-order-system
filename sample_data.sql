CREATE DATABASE IF NOT EXISTS orders_db;
USE orders_db;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2)
);

INSERT INTO products (name, price) VALUES
('Product A', 120.00),
('Product B', 180.00),
('Product C', 250.00);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(50),
    customer_name VARCHAR(100),
    email VARCHAR(100),
    shipping_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    unit_price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    status VARCHAR(50),
    amount DECIMAL(10,2),
    request_payload TEXT,
    response_payload TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE refunds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id INT,
    request_payload TEXT,
    response_payload TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES payments(id)
);
