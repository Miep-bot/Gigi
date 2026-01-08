<?php

class Auth {
        public function verifyPassword($userId, $currentPassword): bool {
            require_once __DIR__ . '/../entities/User.php';
            $userModel = new User();
            $user = $userModel->getById($userId);
            if (!$user) return false;
            return password_verify($currentPassword, $user['password']);
        }

        public function changePassword($userId, $newPassword): void {
            require_once __DIR__ . '/../entities/User.php';
            $userModel = new User();
            $userModel->updatePassword($userId, $newPassword);
        }
    public static function check(): void {
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }
    }

    public static function admin(): void {
        self::check();

        if (!$_SESSION['user']['is_admin']) {
            http_response_code(403);
            echo "Geen toegang.";
            exit;
        }
    }
}