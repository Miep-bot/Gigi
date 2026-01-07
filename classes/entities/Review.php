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

    public function getAverageByProduct(int $productId): array {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS cnt, AVG(rating) AS avg_rating FROM reviews WHERE product_id = :product"
        );
        $stmt->execute(['product' => $productId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            'count' => (int)($row['cnt'] ?? 0),
            'average' => $row['avg_rating'] !== null ? round((float)$row['avg_rating'], 1) : 0.0
        ];
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
            (user_id, product_id, rating, title, comment, is_verified, creation_time)
            VALUES (:user, :product, :rating, :title, :comment, :is_verified, NOW())"
        );

        $stmt->execute([
            'user' => $userId,
            'product' => $productId,
            'rating' => $rating,
            'title' => $title,
            'comment' => $comment,
            'is_verified' => 0
        ]);
    }
}