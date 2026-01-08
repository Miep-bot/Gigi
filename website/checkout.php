<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

require_once __DIR__ . '/../classes/controllers/OrderController.php';
require_once __DIR__ . '/../classes/controllers/CartController.php';
require_once __DIR__ . '/../classes/config/Security.php';

session_start();

$controller = new OrderController();
$cartController = new CartController();
$message = "";

 
$cartItems = $cartController->getCartItems();

 
$groupedItems = [];
foreach ($cartItems as $item) {
    $productId = (int)$item['id'];
    if (!isset($groupedItems[$productId])) {
        $groupedItems[$productId] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => (int)$item['price'],
            'quantity' => 0
        ];
    }
    $groupedItems[$productId]['quantity']++;
}

$orderTotal = 0;
foreach ($groupedItems as $item) {
    $orderTotal += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->checkout();

    if ($result === "success") {
        header("Location: orders.php");
        exit;
    }

    $message = $result;
}
?>

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<div class="container">
    <h1>Checkout</h1>

    <div class="checkout-summary">
        <h2>Order Summary</h2>
        <ul>
            <?php foreach ($groupedItems as $item): ?>
                <li style="display: flex; justify-content: space-between; padding: 0.5em 0; border-bottom: 1px solid #eee;">
                    <div>
                        <strong><?= Security::escape($item['name']) ?></strong>
                        <span style="color: #666;"> × <?= $item['quantity'] ?></span>
                    </div>
                    <span><?= (int)$item['price'] * $item['quantity'] ?> coins</span>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="checkout-total">
            <strong>Total: <?= $orderTotal ?> coins</strong>
        </div>
        <p style="color: #666; font-size: 0.9em;">Your balance: <?= (int)$_SESSION['user']['coins'] ?> coins</p>
    </div>

    <form method="post" style="margin-top: 2em;" id="checkoutForm">
        <button type="button" onclick="showPaymentConfirmation()">Place order</button>
    </form>

    <p><?= htmlspecialchars($message) ?></p>

    <hr>
    <a href="cart.php"><button>← Back to Cart</button></a>
</div>

<!-- Payment Confirmation Popup -->
<div id="payment-confirmation" class="payment-confirmation-overlay" style="display: none;">
    <div class="payment-confirmation-box">
        <h2>Confirm Payment</h2>
        <div class="payment-details">
            <p><strong>Total Amount:</strong> <span class="confirmation-total"><?= $orderTotal ?></span> coins</p>
            <p><strong>Current Balance:</strong> <span class="confirmation-balance"><?= (int)$_SESSION['user']['coins'] ?></span> coins</p>
            <p><strong>Balance After Purchase:</strong> <span class="confirmation-remaining"><?= (int)$_SESSION['user']['coins'] - $orderTotal ?></span> coins</p>
        </div>
        <p style="margin-top: 1em; color: #666;">Are you sure you want to proceed with this purchase?</p>
        <div class="confirmation-buttons">
            <button type="button" class="btn-primary" onclick="confirmPayment()">Confirm</button>
            <button type="button" class="btn-secondary" onclick="cancelPayment()">Cancel</button>
        </div>
    </div>
</div>

<script>
function showPaymentConfirmation() {
    document.getElementById('payment-confirmation').style.display = 'flex';
}

function confirmPayment() {
    document.getElementById('checkoutForm').submit();
}

function cancelPayment() {
    document.getElementById('payment-confirmation').style.display = 'none';
}


document.addEventListener('click', function(event) {
    const popup = document.getElementById('payment-confirmation');
    const box = document.querySelector('.payment-confirmation-box');
    if (popup && event.target === popup) {
        popup.style.display = 'none';
    }
});
</script>

<?php include '../style/components/footer.php'; ?>