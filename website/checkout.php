<?php

require_once __DIR__ . '/../classes/controllers/OrderController.php';

$controller = new OrderController();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->checkout();

    if ($result === "success") {
        header("Location: orders.php");
        exit;
    }

    $message = $result;
}
?>

<?php session_start(); ?>
<?php include 'assets/components/nav.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">

<h1>Checkout</h1>

<form method="post">
    <button>Bestelling plaatsen</button>
</form>

<p><?= htmlspecialchars($message) ?></p>