<?php
require_once __DIR__ . '/../classes/controllers/AuthController.php';

$auth = new AuthController();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->login($_POST);

    if ($result === "admin") {
        header("Location: admin.php");
        exit;
    }

    if ($result === "user") {
        header("Location: index.php");
        exit;
    }

    $error = $result;
}
?>

<?php session_start(); ?>
<?php include 'assets/components/nav.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">

<form method="post">
    <input name="email" type="email" placeholder="Email">
    <input name="password" type="password" placeholder="Wachtwoord">
    <button>Login</button>
</form>

<p><?= htmlspecialchars($error) ?></p>