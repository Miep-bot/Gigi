<?php
session_start();

require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/helpers/Security.php';

$controller = new ProductController();

$tagId = isset($_GET['tag']) ? (int)$_GET['tag'] : null;

$products = $controller->getProducts($tagId);
$tags = $controller->getTags();
?>

<h1>Gigi – Games</h1>

<!-- TAG FILTERS -->
<nav>
    <a href="index.php">Alle</a>
    <?php foreach ($tags as $tag): ?>
        <a href="?tag=<?= (int)$tag['id'] ?>">
            <?= Security::escape($tag['tag']) ?>
        </a>
    <?php endforeach; ?>
</nav>

<hr>

<!-- PRODUCTEN -->
<div class="products">
    <?php if (empty($products)): ?>
        <p>Geen producten gevonden.</p>
    <?php endif; ?>

    <?php foreach ($products as $product): ?>
        <div class="product">
            <h3><?= Security::escape($product['name']) ?></h3>

            <?php if ($product['image']): ?>
                <img src="assets/images/<?= Security::escape($product['image']) ?>">
            <?php endif; ?>

            <p><?= Security::escape($product['description'] ?? '') ?></p>
            <strong><?= (int)$product['price'] ?> coins</strong>
        </div>
    <?php endforeach; ?>
</div>