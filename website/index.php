<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

session_start();

require_once __DIR__ . '/../classes/controllers/ProductController.php';
require_once __DIR__ . '/../classes/controllers/ReviewController.php';
require_once __DIR__ . '/../classes/config/Security.php';

$controller = new ProductController();
$reviewController = new ReviewController();

$tagId = isset($_GET['tag']) ? (int)$_GET['tag'] : null;

$products = $controller->getProducts($tagId);
$tags = $controller->getTags();
if ($tagId !== null) {
    $tagIds = array_column($tags, 'id');
    if (!in_array($tagId, $tagIds)) {
        
        require_once __DIR__ . '/../classes/config/Database.php';
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM tags WHERE id = :id');
        $stmt->execute(['id' => $tagId]);
        $selectedTag = $stmt->fetch();
        if ($selectedTag) {
            $tags[] = $selectedTag;
        }
    }
}
?>

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<h1 class="title">Welcome to Gigi!</h1>

<section class="hero">
    <div class="container">
        <h1>Level up your collection</h1>
        <p>get coins, buy games, have fun! 🎮</p>
    </div>
</section>

<nav>
    <a href="index.php" class="filter-start <?= is_null($tagId) ? 'active-tag' : '' ?>">All</a>
    <?php
    $maxInline = 4;
    $inlineTags = array_slice($tags, 0, $maxInline);
    $selectedTagIndex = null;
    if ($tagId !== null) {
        foreach ($tags as $i => $tag) {
            if ((int)$tag['id'] === $tagId) {
                $selectedTagIndex = $i;
                break;
            }
        }

        if ($selectedTagIndex !== null && $selectedTagIndex >= $maxInline) {
            $selectedTag = $tags[$selectedTagIndex];
            $inlineTags[$maxInline-1] = $selectedTag;
        }
    }
    $shown = 0;
    foreach ($inlineTags as $tag):
        $isActive = ($tagId === (int)$tag['id']);
        $classes = [];
        if ($isActive) $classes[] = 'active-tag';
        ?>
        <a href="?tag=<?= (int)$tag['id'] ?>" class="<?= implode(' ', $classes) ?>">
            <?= Security::escape($tag['tag']) ?>
        </a>
    <?php endforeach; ?>
    <?php if (count($tags) > $maxInline): ?>
        <button class="show-tags-btn" onclick="openTagsPopup()">Show all tags</button>
    <?php endif; ?>
</nav>

<?php if (count($tags) > $maxInline): ?>
<div id="tags-popup-overlay" class="tags-popup-overlay" style="display:none;">
    <div class="tags-popup">
        <div class="tags-popup-header">
            <span>All Tags</span>
            <button class="close-tags-btn" onclick="closeTagsPopup()">&times;</button>
        </div>
        <div class="tags-popup-list">
            <a href="index.php" class="filter-start <?= is_null($tagId) ? 'active-tag' : '' ?>">All</a>
            <?php foreach ($tags as $tag): ?>
                <?php $isActive = ($tagId === (int)$tag['id']); $classes = [];
                if ($isActive) $classes[] = 'active-tag'; ?>
                <a href="?tag=<?= (int)$tag['id'] ?>" class="<?= implode(' ', $classes) ?>">
                    <?= Security::escape($tag['tag']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script src="../style/js/tags-popup.js"></script>
<?php endif; ?>

<hr>

<div class="products">
    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php endif; ?>

    <?php foreach ($products as $product): ?>
        <div class="product">
            <a class="product-link" href="product.php?id=<?= (int)$product['id'] ?>">
                <h3><?= Security::escape($product['name']) ?></h3>

                <?php if ($product['image']): ?>
                    <img src="assets/images/<?= Security::escape($product['image']) ?>" alt="<?= Security::escape($product['name']) ?>">
                <?php endif; ?>

                <p><?= Security::escape($product['description'] ?? '') ?></p>
                <?php $avg = $reviewController->getAverageByProduct((int)$product['id']); ?>
                <div class="card-rating">
                    <span class="star-display">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <span class="star <?= ($i < floor($avg['average'])) ? 'filled' : 'empty' ?>">★</span>
                        <?php endfor; ?>
                    </span>
                    <span class="rating-count">(<?= $avg['average'] ?>/5 from <?= $avg['count'] ?>)</span>
                </div>
                <strong><?= (int)$product['price'] ?> coins</strong>
            </a>

            <form onsubmit="addToCartWithPopup(event, <?= (int)$product['id'] ?>)">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <button type="submit">In cart</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<?php include '../style/components/footer.php'; ?>