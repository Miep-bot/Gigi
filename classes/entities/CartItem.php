<?php

require_once __DIR__ . '/../config/Database.php';

class CartItem {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function add(int $cartId, int $productId): void {
        $stmt = $this->db->prepare(
            "INSERT INTO cartitems (cart_id, product_id)
             VALUES (:cart, :product)"
        );

        $stmt->execute([
            'cart' => $cartId,
            'product' => $productId
        ]);
    }

    public function remove(int $cartItemId): void {
        $stmt = $this->db->prepare(
            "DELETE FROM cartitems WHERE id = :id"
        );
        $stmt->execute(['id' => $cartItemId]);
    }

    public function getItems(int $cartId): array {
        $stmt = $this->db->prepare(
            "SELECT ci.id AS cartitem_id, p.*
             FROM cartitems ci
             JOIN products p ON ci.product_id = p.id
             WHERE ci.cart_id = :cart"
        );

        $stmt->execute(['cart' => $cartId]);
        return $stmt->fetchAll();
    }
}