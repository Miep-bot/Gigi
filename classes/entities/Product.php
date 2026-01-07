<?php

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($title, $price, $categoryId) {
        if ($price <= 0) {
            throw new Exception("Prijs moet groter zijn dan 0");
        }

        $stmt = $this->db->prepare(
            "INSERT INTO products (title, price, category_id)
             VALUES (:title, :price, :category)"
        );

        $stmt->execute([
            'title' => $title,
            'price' => $price,
            'category' => $categoryId
        ]);
    }
}