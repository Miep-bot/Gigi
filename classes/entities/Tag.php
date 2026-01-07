<?php

require_once __DIR__ . '/../config/Database.php';

class Tag {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $stmt = $this->db->prepare("SELECT * FROM tags ORDER BY tag");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(string $tag): int {
        $stmt = $this->db->prepare("INSERT INTO tags (tag) VALUES (:tag)");
        $stmt->execute(['tag' => $tag]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $tag): void {
        $stmt = $this->db->prepare("UPDATE tags SET tag = :tag WHERE id = :id");
        $stmt->execute(['tag' => $tag, 'id' => $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM tags WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getByProduct(int $productId): array {
    $stmt = $this->db->prepare(
        "SELECT t.*
         FROM tags t
         JOIN productTags pt ON t.id = pt.tag_id
         WHERE pt.product_id = :product"
    );
    $stmt->execute(['product' => $productId]);
    return $stmt->fetchAll();
}

    public function setForProduct(int $productId, array $tagIds): void {
        $stmt = $this->db->prepare(
            "DELETE FROM productTags WHERE product_id = :product"
        );
        $stmt->execute(['product' => $productId]);

        $stmt = $this->db->prepare(
            "INSERT INTO productTags (product_id, tag_id)
            VALUES (:product, :tag)"
        );

        foreach ($tagIds as $tagId) {
            $stmt->execute([
                'product' => $productId,
                'tag' => $tagId
            ]);
        }
    }
}