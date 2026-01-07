<?php

require_once __DIR__ . '/../classes/controllers/AuthController.php';

$auth = new AuthController();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $auth->register($_POST);
}
?>

<?php session_start(); ?>
<?php include 'assets/components/nav.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">

<form method="post">
    <input name="first_name" placeholder="Voornaam">
    <input name="last_name" placeholder="Achternaam">
    <input name="email" type="email" placeholder="Email">
    <input name="password" type="password" placeholder="Wachtwoord">
    <button>Registreren</button>
</form>

<p><?= htmlspecialchars($message) ?></p>