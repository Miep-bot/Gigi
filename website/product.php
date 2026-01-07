<?php

session_start();

require_once __DIR__ . '/../classes/controllers/ProductController.php';
require_once __DIR__ . '/../classes/controllers/ReviewController.php';
require_once __DIR__ . '/../classes/config/Security.php';

$productController = new ProductController();
$reviewController = new ReviewController();

$productId = (int)($_GET['id'] ?? 0);

$products = $productController->getProducts();
$product = null;

foreach ($products as $p) {
    if ($p['id'] == $productId) {
        $product = $p;
        break;
    }
}

if (!$product) {
    echo "Product niet gevonden.";
    exit;
}

$reviews = $reviewController->getByProduct($productId);
?>

<?php session_start(); ?>
<?php include 'assets/components/nav.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">

<h1><?= Security::escape($product['name']) ?></h1>
<p><?= Security::escape($product['description'] ?? '') ?></p>
<strong><?= (int)$product['price'] ?> coins</strong>

<hr>

<h2>Reviews</h2>

<?php if (empty($reviews)): ?>
    <p>Nog geen reviews.</p>
<?php endif; ?>

<?php foreach ($reviews as $review): ?>
    <div class="review">
        <strong><?= Security::escape($review['first_name']) ?></strong>
        ⭐ <?= (int)$review['rating'] ?>/5

        <?php if ($review['title']): ?>
            <h4><?= Security::escape($review['title']) ?></h4>
        <?php endif; ?>

        <?php if ($review['comment']): ?>
            <p><?= Security::escape($review['comment']) ?></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<hr>

<?php if (isset($_SESSION['user'])): ?>
<h3>Review plaatsen</h3>

<form method="post" action="add_review.php">
    <input type="hidden" name="product_id" value="<?= $productId ?>">

    <label>Rating</label>
    <select name="rating">
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
    </select>

    <input name="title" placeholder="Titel (optioneel)">
    <textarea name="comment" placeholder="Comment (optioneel)"></textarea>

    <button>Verstuur review</button>
</form>
<?php else: ?>
<p><a href="login.php">Log in</a> om een review te plaatsen.</p>
<?php endif; ?>