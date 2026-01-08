<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

session_start();
require_once __DIR__ . '/../classes/config/Auth.php';

Auth::admin();

require_once __DIR__ . '/../classes/controllers/AdminController.php';
require_once __DIR__ . '/../classes/config/Security.php';

$admin = new AdminController();
$products = $admin->getProducts();
$tags = $admin->getTags();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_tag' && !empty($_POST['tag'])) {
        $admin->createTag(trim($_POST['tag']));
        header('Location: admin.php'); exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'edit_tag' && !empty($_POST['tag']) && !empty($_POST['tag_id'])) {
        $admin->updateTag((int)$_POST['tag_id'], trim($_POST['tag']));
        header('Location: admin.php'); exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete_tag' && !empty($_POST['tag_id'])) {
        $admin->deleteTag((int)$_POST['tag_id']);
        header('Location: admin.php'); exit;
    }
}
?>

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<div class="container">
    <div class="admin-header">
        <h1>Admin dashboard</h1>
        <a href="admin_product_form.php" class="btn-primary">New product</a>
    </div>

    <div class="products-grid admin-products">
        <?php if (empty($products)): ?>
            <p>No products found.</p>
        <?php endif; ?>

        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-card-body">
                    <h3><?= Security::escape($product['name']) ?></h3>
                    <div class="product-price"><?= (int)$product['price'] ?> coins</div>
                </div>

                <div class="product-card-actions">
                    <a class="action-edit" href="admin_product_form.php?id=<?= (int)$product['id'] ?>">✏️ Edit</a>
                    <a class="action-delete" href="admin_delete_product.php?id=<?= (int)$product['id'] ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<hr>

<div class="container admin-tags">
    <h2 class="admin-section-title">Manage Tags</h2>
    <h3>Existing Tags</h3>
    <table class="tags-table">
        <tr><th>Tag</th><th>Actions</th></tr>
        <?php foreach ($tags as $tag): ?>
        <tr>
            <td><?= Security::escape($tag['tag']) ?></td>
            <td>
                <form method="post" style="display:inline">
                    <input type="hidden" name="tag_id" value="<?= (int)$tag['id'] ?>">
                    <input type="hidden" name="action" value="delete_tag">
                    <button onclick="return confirm('Delete this tag?')">🗑️</button>
                </form>

                <button onclick="document.getElementById('edit-tag-<?= (int)$tag['id'] ?>').style.display='block'">✏️</button>

                <div id="edit-tag-<?= (int)$tag['id'] ?>" class="edit-tag" style="display:none; margin-top:0.5rem;">
                    <form method="post">
                        <input type="hidden" name="action" value="edit_tag">
                        <input type="hidden" name="tag_id" value="<?= (int)$tag['id'] ?>">
                        <input name="tag" value="<?= Security::escape($tag['tag']) ?>">
                        <button type="submit">Save</button>
                        <button type="button" onclick="this.closest('.edit-tag').style.display='none'">Cancel</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3 style="margin-top:1rem">Add Tag</h3>
    <form method="post" class="add-tag-form">
        <input type="hidden" name="action" value="add_tag">
        <input name="tag" placeholder="New tag name">
        <button type="submit">Add Tag</button>
    </form>
</div>

<?php include '../style/components/footer.php'; ?>