<?php
session_start();
require_once __DIR__ . '/../classes/config/Auth.php';
require_once __DIR__ . '/../classes/config/Security.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $userId = $_SESSION['user']['id'];

    if (empty($current) || empty($new) || empty($confirm)) {
        $message = 'Please fill in all fields.';
    } elseif ($new !== $confirm) {
        $message = 'New passwords do not match.';
    } else {
        $auth = new Auth();
        if (!$auth->verifyPassword($userId, $current)) {
            $message = 'Current password is incorrect.';
        } elseif (strlen($new) < 6) {
            $message = 'New password must be at least 6 characters.';
        } else {
            $auth->changePassword($userId, $new);
            $message = 'Password changed successfully!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="../style/css/style.css">
</head>
<body>
<?php include '../style/components/nav.php'; ?>
<div class="auth-container">
    <h2>Change Password</h2>
    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
            <?= Security::escape($message) ?>
        </div>
    <?php endif; ?>
    <form method="post" class="auth-form">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit">Change Password</button>
    </form>
</div>
<?php include '../style/components/footer.php'; ?>
</body>
</html>
