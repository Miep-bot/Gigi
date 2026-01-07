<?php
require_once __DIR__ . '/../classes/controllers/CartController.php';
require_once __DIR__ . '/../classes/config/Security.php';

$controller = new CartController();
$items = $controller->getCartItems();
?>

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<h1>Winkelmandje</h1>

<?php if (empty($items)): ?>
    <p>Je winkelmandje is leeg.</p>
<?php endif; ?>

<ul>
    <?php foreach ($items as $item): ?>
        <li>
            <?= Security::escape($item['name']) ?>
            (<?= (int)$item['price'] ?> coins)

            <form method="post" action="cart.php" style="display:inline">
                <input type="hidden" name="remove" value="<?= (int)$item['cartitem_id'] ?>">
                <button>Verwijder</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

<?php
if (isset($_POST['remove'])) {
    $controller->removeItem((int)$_POST['remove']);
}
?>