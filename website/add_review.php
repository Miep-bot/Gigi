<?php
require_once __DIR__ . '/../classes/controllers/ReviewController.php';

$controller = new ReviewController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->add($_POST);

    header("Location: product.php?id=" . (int)$_POST['product_id']);
    exit;
}