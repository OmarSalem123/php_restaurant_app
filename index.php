<?php

const PRODUCT_SELECT = 'SELECT p.id, p.name, p.description, p.price, p.icon, p.tags, c.slug AS category FROM products p JOIN categories c ON c.id = p.category_id';

require "config/database.php";

header("Content-Type: application/json; charset=UTF-8");

if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
} 

$stmt = db()->query(PRODUCT_SELECT . ' WHERE p.is_active = 1')->fetchAll();

echo json_encode($stmt);
?>