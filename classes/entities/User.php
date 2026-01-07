<?php

require_once __DIR__ . '/../config/Database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = :email"
        );
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ): bool {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO users 
            (first_name, last_name, email, password, is_admin, coins, creation_time)
            VALUES (:first, :last, :email, :password, 0, 0, NOW())"
        );

        return $stmt->execute([
            'first' => $firstName,
            'last' => $lastName,
            'email' => $email,
            'password' => $passwordHash
        ]);
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function updateCoins(int $userId, int $newAmount): void {
        $stmt = $this->db->prepare(
            "UPDATE users SET coins = :coins WHERE id = :id"
        );
        $stmt->execute([
            'coins' => $newAmount,
            'id' => $userId
        ]);
    }
}