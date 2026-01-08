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
        int $price,
        ?string $image = null
    ): int {
        if ($price <= 0) {
            throw new Exception("Prijs moet groter zijn dan 0");
        }
        $stmt = $this->db->prepare(
            "INSERT INTO products (name, description, price, image, creation_time)
            VALUES (:name, :description, :price, :image, UNIX_TIMESTAMP())"
        );
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image' => $image
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(
        int $id,
        string $name,
        ?string $description,
        int $price,
        ?string $image = null
    ): void {
        if ($price <= 0) {
            throw new Exception("Prijs moet groter zijn dan 0");
        }
        $sql = "UPDATE products SET name = :name, description = :description, price = :price";
        $params = [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price
        ];
        if ($image !== null) {
            $sql .= ", image = :image";
            $params['image'] = $image;
        }
        $sql .= " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function delete(int $id): void {
        // Remove from cartitems
        $stmt = $this->db->prepare("DELETE FROM cartitems WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
        // Remove from productTags
        $stmt = $this->db->prepare("DELETE FROM productTags WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
        // Remove from orderitems (required by foreign key)
        $stmt = $this->db->prepare("DELETE FROM orderitems WHERE product_id = :id");
        $stmt->execute(['id' => $id]);
        // Now delete the product
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}