<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

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
    echo "Product not found.";
    exit;
}

$reviews = $reviewController->getByProduct($productId);
?>

<?php if (isset($_GET['review_submitted'])): ?>
    <div class="message success">Thanks — your review was submitted.</div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="message error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<div class="container">
    <h1><?= Security::escape($product['name']) ?></h1>
    <p><?= Security::escape($product['description'] ?? '') ?></p>
    <strong><?= (int)$product['price'] ?> coins</strong>

    <form onsubmit="addToCartWithPopup(event, <?= (int)$product['id'] ?>)" style="margin-bottom: 2em;">
        <button type="submit">Add to Cart</button>
    </form>

    <hr>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <h2>Reviews</h2>

        <?php if (!empty($reviews)): ?>
            <?php 
            $totalRating = 0;
            $reviewCount = count($reviews);
            foreach ($reviews as $review) {
                $totalRating += (int)$review['rating'];
            }
            $averageRating = $reviewCount > 0 ? round($totalRating / $reviewCount, 1) : 0;
            ?>
            <div class="average-rating">
                <strong>Average Rating: </strong>
                <span class="star-display">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <span class="star <?= ($i < floor($averageRating)) ? 'filled' : 'empty' ?>">★</span>
                    <?php endfor; ?>
                </span>
                <span>(<?= $averageRating ?>/5 from <?= $reviewCount ?> review<?= $reviewCount !== 1 ? 's' : '' ?>)</span>
            </div>
        <?php else: ?>
            <p style="color: #999;">No reviews yet. Be the first to review this product!</p>
        <?php endif; ?>

        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <div class="review-header">
                    <strong><?= Security::escape($review['first_name']) ?></strong>
                    <div class="review-rating">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <span class="star <?= ($i < (int)$review['rating']) ? 'filled' : 'empty' ?>">★</span>
                        <?php endfor; ?>
                        <span class="rating-text"><?= (int)$review['rating'] ?>/5</span>
                    </div>
                </div>

                <?php if ($review['title']): ?>
                    <h4><?= Security::escape($review['title']) ?></h4>
                <?php endif; ?>

                <?php if ($review['comment']): ?>
                    <p><?= Security::escape($review['comment']) ?></p>
                <?php endif; ?>

                <div class="review-date"><?= date('d M Y', strtotime($review['creation_time'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <hr>

    <!-- Review Form Section -->
    <?php if (isset($_SESSION['user'])): ?>
    <div class="review-form-section">
        <h3>Leave a Review</h3>
        <form method="post" action="add_review.php" class="review-form">
            <input type="hidden" name="product_id" value="<?= $productId ?>">

            <div class="form-group">
                <label for="rating">Rating *</label>
                <div class="rating-selector">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <label class="rating-option">
                            <input type="radio" name="rating" value="<?= $i ?>" required>
                            <span class="rating-star">★</span>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="title">Title (optional)</label>
                <input type="text" id="title" name="title" placeholder="Brief title for your review" maxlength="100">
            </div>

            <div class="form-group">
                <label for="comment">Comment (optional)</label>
                <textarea id="comment" name="comment" placeholder="Share your thoughts about this product..." maxlength="500" rows="4"></textarea>
            </div>

            <button type="submit" class="btn-submit">Submit Review</button>
        </form>
    </div>
    <?php else: ?>
    <div class="login-prompt">
        <p><a href="login.php">Log in</a> to place a review.</p>
    </div>
    <?php endif; ?>
</div>

<hr>
<a href="index.php"><button>← Back to products</button></a>
<a href="cart.php" style="margin-left: 1em;"><button>Go to Cart →</button></a>

<?php include '../style/components/footer.php'; ?>