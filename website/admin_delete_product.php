<?php
require_once __DIR__ . '/../classes/controllers/AdminController.php';

$admin = new AdminController();

if (isset($_GET['id'])) {
    $admin->deleteProduct((int)$_GET['id']);
}

header("Location: admin.php");
exit;