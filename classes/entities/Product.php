<?php

require_once __DIR__ . '/../config/Database.php';

class Product {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM products ORDER BY creation_time DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByTag(int $tagId): array {
        $stmt = $this->db->prepare(
            "SELECT p.*
             FROM products p
             JOIN productTags pt ON p.id = pt.product_id
             WHERE pt.tag_id = :tagId
             ORDER BY p.creation_time DESC"
        );

        $stmt->execute(['tagId' => $tagId]);
        return $stmt->fetchAll();
    }
}