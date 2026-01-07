<?php
require_once __DIR__ . '/../classes/controllers/CartController.php';

$controller = new CartController();

if (isset($_POST['product_id'])) {
    $controller->addProduct((int)$_POST['product_id']);
}