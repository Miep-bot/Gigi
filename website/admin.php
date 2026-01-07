<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

session_start();
require_once __DIR__ . '/../classes/config/Auth.php';

Auth::admin();

require_once __DIR__ . '/../classes/controllers/AdminController.php';
require_once __DIR__ . '/../classes/config/Security.php';

$admin = new AdminController();
$products = $admin->getProducts();
?>

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<h1>Admin dashboard</h1>

<a href="admin_product_form.php">➕ Nieuw product</a>

<table border="1">
    <tr>
        <th>Naam</th>
        <th>Prijs</th>
        <th>Acties</th>
    </tr>

    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= Security::escape($product['name']) ?></td>
            <td><?= (int)$product['price'] ?> coins</td>
            <td>
                <a href="admin_product_form.php?id=<?= (int)$product['id'] ?>">✏️</a>
                <a href="admin_delete_product.php?id=<?= (int)$product['id'] ?>"
                   onclick="return confirm('Zeker weten?')">🗑️</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>