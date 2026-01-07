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
}