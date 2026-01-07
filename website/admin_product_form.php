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

<h1><?= $product ? 'Product bewerken' : 'Nieuw product' ?></h1>

<form method="post">
    <?php if ($product): ?>
        <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
    <?php endif; ?>

    <input name="name" placeholder="Naam"
           value="<?= Security::escape($product['name'] ?? '') ?>">

    <textarea name="description"><?= Security::escape($product['description'] ?? '') ?></textarea>

    <input type="number" name="price"
           value="<?= (int)($product['price'] ?? 0) ?>">

    <h3>Tags</h3>
    <?php foreach ($tags as $tag): ?>
        <label>
            <input type="checkbox" name="tags[]"
                   value="<?= (int)$tag['id'] ?>"
                   <?= in_array($tag['id'], $productTags) ? 'checked' : '' ?>>
            <?= Security::escape($tag['tag']) ?>
        </label><br>
    <?php endforeach; ?>

    <button>Opslaan</button>
</form>