<?php

require_once __DIR__ . '/../classes/controllers/AuthController.php';

$auth = new AuthController();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->login($_POST);

    if ($result === "success") {
        header("Location: index.php");
        exit;
    }

    $error = $result;
}
?>

<form method="post">
    <input name="email" type="email" placeholder="Email">
    <input name="password" type="password" placeholder="Wachtwoord">
    <button>Login</button>
</form>

<p><?= htmlspecialchars($error) ?></p>