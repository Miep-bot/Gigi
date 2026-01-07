<?php
session_start();

header('Content-Type: application/json');

// Check if user is logged in
if (empty($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in to add items to your cart',
        'requiresLogin' => true
    ]);
    exit;
}

require_once __DIR__ . '/../classes/controllers/CartController.php';

try {
    $controller = new CartController();

    if (isset($_POST['product_id'])) {
        $controller->addProduct((int)$_POST['product_id']);
        $items = $controller->getCartItems();
        $cartCount = count($items);
        
        echo json_encode([
            'success' => true,
            'cartCount' => $cartCount
        ]);
        exit;
    }

    echo json_encode([
        'success' => false,
        'message' => 'No product ID provided'
    ]);
    exit;
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error adding item to cart: ' . $e->getMessage()
    ]);
    exit;
}