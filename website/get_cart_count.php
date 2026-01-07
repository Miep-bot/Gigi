<?php
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../classes/controllers/CartController.php';

$controller = new CartController();
$items = $controller->getCartItems();
$cartCount = count($items);

echo json_encode([
    'cartCount' => $cartCount
]);
