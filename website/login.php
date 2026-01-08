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

<?php include '../style/components/nav.php'; ?>
<link rel="stylesheet" href="../style/css/style.css">
<div class="container auth-container">
    <h1>Login</h1>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="auth-form">
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="you@example.com" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Password" required>
        </div>

        <button class="btn-primary" type="submit">Login</button>
    </form>

    <hr>
    <p>Don't have an account? 
<a href="register.php">Register here</a></p>
</div>

<?php include '../style/components/footer.php'; ?>