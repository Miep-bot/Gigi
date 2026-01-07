<?php

require_once __DIR__ . '/../classes/controllers/AuthController.php';

$auth = new AuthController();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $auth->register($_POST);
}
?>

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">

<form method="post">
    <input name="first_name" placeholder="First name">
    <input name="last_name" placeholder="Last name">
    <input name="email" type="email" placeholder="Email">
    <input name="password" type="password" placeholder="Password">
    <button>Register</button>
</form>

<p><?= htmlspecialchars($message) ?></p>

<hr>
<p>Already have an account? <a href="login.php"><button>Login here</button></a></p>