<?php

require_once __DIR__ . '/../config/Database.php';

class Cart {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getOrCreateByUser(int $userId): int {
        $stmt = $this->db->prepare(
            "SELECT id FROM carts WHERE user_id = :user"
        );
        $stmt->execute(['user' => $userId]);

        $cart = $stmt->fetch();

        if ($cart) {
            return (int)$cart['id'];
        }

        $stmt = $this->db->prepare(
            "INSERT INTO carts (user_id) VALUES (:user)"
        );
        $stmt->execute(['user' => $userId]);

        return (int)$this->db->lastInsertId();
    }
}