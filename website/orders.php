<?php

require_once __DIR__ . '/../classes/controllers/OrderController.php';
require_once __DIR__ . '/../classes/config/Security.php';

$controller = new OrderController();
$orders = $controller->getOrders();
?>

<?php session_start(); ?>
<?php include 'assets/components/nav.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">

<h1>Mijn bestellingen</h1>

<?php if (empty($orders)): ?>
    <p>Geen bestellingen.</p>
<?php endif; ?>

<?php foreach ($orders as $order): ?>
    <h3>Order #<?= (int)$order['id'] ?></h3>
    <ul>
        <?php
        $items = $controller->getOrderItems((int)$order['id']);
        foreach ($items as $item):
        ?>
            <li><?= Security::escape($item['name']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>