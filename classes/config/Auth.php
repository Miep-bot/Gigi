<?php

class Auth {
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