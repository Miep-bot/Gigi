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

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM products WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(
        string $name,
        ?string $description,
        int $price
    ): int {
        if ($price <= 0) {
            throw new Exception("Prijs moet groter zijn dan 0");
        }

        $stmt = $this->db->prepare(
            "INSERT INTO products (name, description, price, creation_time)
            VALUES (:name, :description, :price, UNIX_TIMESTAMP())"
        );

        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(
        int $id,
        string $name,
        ?string $description,
        int $price
    ): void {
        if ($price <= 0) {
            throw new Exception("Prijs moet groter zijn dan 0");
        }

        $stmt = $this->db->prepare(
            "UPDATE products
            SET name = :name,
                description = :description,
                price = :price
            WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare(
            "DELETE FROM products WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
    }
}