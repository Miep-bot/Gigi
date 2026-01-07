<?php
session_start();

require_once __DIR__ . '/../classes/controllers/CartController.php';
require_once __DIR__ . '/../classes/config/Security.php';

$controller = new CartController();

// If user is not logged in, redirect to login page instead of letting the controller throw
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['remove'])) {
    $controller->removeItem((int)$_POST['remove']);
}

if (isset($_POST['removeAll'])) {
    $cartItemIds = explode(',', $_POST['removeAll']);
    foreach ($cartItemIds as $id) {
        $controller->removeItem((int)trim($id));
    }
}

if (isset($_POST['increase'])) {
    // Add the same product again
    $controller->addProduct((int)$_POST['increase']);
}

if (isset($_POST['decrease'])) {
    $cartItemIds = explode(',', $_POST['decrease']);
    // Remove only the first one (most recent)
    if (!empty($cartItemIds)) {
        $controller->removeItem((int)trim($cartItemIds[0]));
    }
}

$items = $controller->getCartItems();

// Group items by product and calculate quantities
$groupedItems = [];
foreach ($items as $item) {
    $productId = (int)$item['id'];
    if (!isset($groupedItems[$productId])) {
        $groupedItems[$productId] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => (int)$item['price'],
            'cartitem_ids' => [],
            'quantity' => 0
        ];
    }
    $groupedItems[$productId]['cartitem_ids'][] = (int)$item['cartitem_id'];
    $groupedItems[$productId]['quantity']++;
}
?>

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<div class="container">
    <h1>Cart</h1>

    <?php if (empty($items)): ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <ul>
        <?php 
        $total = 0;
        foreach ($groupedItems as $item): 
            $itemTotal = (int)$item['price'] * $item['quantity'];
            $total += $itemTotal;
        ?>
            <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.8em 0; border-bottom: 1px solid #eee;">
                <div>
                    <strong><?= Security::escape($item['name']) ?></strong>
                    <div style="color: #666; font-size: 0.9em;"><?= (int)$item['price'] ?> coins each</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1em;">
                    <div class="quantity-controls">
                        <form method="post" action="cart.php" style="display:inline">
                            <input type="hidden" name="decrease" value="<?= implode(',', $item['cartitem_ids']) ?>">
                            <button type="submit" class="qty-btn">−</button>
                        </form>
                        <span class="qty-display"><?= $item['quantity'] ?></span>
                        <form method="post" action="cart.php" style="display:inline">
                            <input type="hidden" name="increase" value="<?= (int)$item['id'] ?>">
                            <button type="submit" class="qty-btn">+</button>
                        </form>
                    </div>

                    <form method="post" action="cart.php" style="display:inline">
                        <input type="hidden" name="removeAll" value="<?= implode(',', $item['cartitem_ids']) ?>">
                        <button class="btn-remove">Remove all</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if (!empty($items)): ?>
    <div class="cart-total">
        <strong>Total: <?= $total ?> coins</strong>
    </div>
    <?php endif; ?>

    <hr>
    <a href="index.php"><button>← Continue Shopping</button></a>
    <?php if (!empty($items)): ?>
        <a href="checkout.php" style="margin-left: 1em;"><button>Proceed to Checkout →</button></a>
    <?php endif; ?>
</div>