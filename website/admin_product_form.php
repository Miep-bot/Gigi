<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<?php
require_once __DIR__ . '/../classes/controllers/AdminController.php';
require_once __DIR__ . '/../classes/config/Security.php';

$admin = new AdminController();

$product = null;
$productTags = [];
$tags = $admin->getTags();

if (isset($_GET['id'])) {
    $product = $admin->getProduct((int)$_GET['id']);
    $productTags = array_column(
        $admin->getProductTags((int)$_GET['id']),
        'id'
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin->saveProduct($_POST);
    header("Location: admin.php");
    exit;
}
?>

<div class="container">
    <h1 class="admin-title"><?= $product ? 'Product bewerken' : 'Nieuw product' ?></h1>

    <form method="post" class="product-form">
        <?php if ($product): ?>
            <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-col">
                <label for="name">Naam</label>
                <input id="name" name="name" placeholder="Naam"
                       value="<?= Security::escape($product['name'] ?? '') ?>">

                <label for="description">Beschrijving</label>
                <textarea id="description" name="description" rows="6"><?= Security::escape($product['description'] ?? '') ?></textarea>
            </div>

            <div class="form-col">
                <label for="price">Prijs (coins)</label>
                <input id="price" type="number" name="price"
                       value="<?= (int)($product['price'] ?? 0) ?>">

                <h3>Tags</h3>
                <div class="tags-grid">
                <?php foreach ($tags as $tag): ?>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="tags[]"
                               value="<?= (int)$tag['id'] ?>"
                               <?= in_array($tag['id'], $productTags) ? 'checked' : '' ?>>
                        <span><?= Security::escape($tag['tag']) ?></span>
                    </label>
                <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Opslaan</button>
            <a href="admin.php" class="btn-secondary-link"><button type="button">Annuleren</button></a>
        </div>
    </form>

    <hr>
    <a href="admin.php"><button>← Back to Admin</button></a>
</div>