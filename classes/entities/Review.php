<?php

require_once __DIR__ . '/../config/Database.php';

class Review {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByProduct(int $productId): array {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.first_name
             FROM reviews r
             JOIN users u ON r.user_id = u.id
             WHERE r.product_id = :product
             ORDER BY r.creation_time DESC"
        );
        $stmt->execute(['product' => $productId]);
        return $stmt->fetchAll();
    }

    public function create(
        int $userId,
        int $productId,
        int $rating,
        ?string $title,
        ?string $comment
    ): void {
        if ($rating < 1 || $rating > 5) {
            throw new Exception("Rating moet tussen 1 en 5 liggen.");
        }

        $stmt = $this->db->prepare(
            "INSERT INTO reviews
            (user_id, product_id, rating, title, comment, creation_time)
            VALUES (:user, :product, :rating, :title, :comment, NOW())"
        );

        $stmt->execute([
            'user' => $userId,
            'product' => $productId,
            'rating' => $rating,
            'title' => $title,
            'comment' => $comment
        ]);
    }
}