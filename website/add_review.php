<?php
session_start();

require_once __DIR__ . '/../classes/controllers/ReviewController.php';

// Check if user is logged in
if (empty($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$controller = new ReviewController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->add($_POST);

    if ($result === "success") {
        header("Location: product.php?id=" . (int)$_POST['product_id'] . "&review_submitted=1");
    } else {
        header("Location: product.php?id=" . (int)$_POST['product_id'] . "&error=" . urlencode($result));
    }
    exit;
}

header("Location: index.php");
exit;
?>