<?php

require_once __DIR__ . '/../classes/controllers/OrderController.php';
require_once __DIR__ . '/../classes/config/Security.php';

$controller = new OrderController();
$orders = $controller->getOrders();
?>

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<div class="container">
    <h1>My orders</h1>

    <?php if (empty($orders)): ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <?php foreach ($orders as $order): ?>
        <div class="order-item">
            <button class="order-header" onclick="toggleOrder(this)">
                <span class="order-toggle">▼</span>
                Order #<?= (int)$order['id'] ?> - <?= $order['creation_time'] ?>
            </button>
            <div class="order-details" style="display: none;">
                <ul>
                    <?php
                    $items = $controller->getOrderItems((int)$order['id']);
                    

                    $groupedItems = [];
                    foreach ($items as $item) {
                        $productId = (int)$item['id'];
                        if (!isset($groupedItems[$productId])) {
                            $groupedItems[$productId] = [
                                'id' => $item['id'],
                                'name' => $item['name'],
                                'price' => (int)$item['price'],
                                'quantity' => 0
                            ];
                        }
                        $groupedItems[$productId]['quantity']++;
                    }
                    
                    $total = 0;
                    foreach ($groupedItems as $item):
                        $itemTotal = (int)$item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    ?>
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.5em 0; border-bottom: 1px solid #eee;">
                            <div>
                                <strong><?= Security::escape($item['name']) ?></strong>
                                <span style="color: #666;"> × <?= $item['quantity'] ?></span>
                            </div>
                            <span class="item-price"><?= $itemTotal ?> coins</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="order-total">
                    Total: <strong><?= $total ?> coins</strong>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include '../style/components/footer.php'; ?>

<script>
function toggleOrder(button) {
    const details = button.nextElementSibling;
    const toggle = button.querySelector('.order-toggle');
    
    if (details.style.display === 'none') {
        details.style.display = 'block';
        toggle.textContent = '▲';
    } else {
        details.style.display = 'none';
        toggle.textContent = '▼';
    }
}
</script>