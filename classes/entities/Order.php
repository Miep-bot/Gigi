<?php

require_once __DIR__ . '/../config/Database.php';

class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(int $userId, array $items): int {
        $this->db->beginTransaction();

        try {
            
            $stmt = $this->db->prepare(
                "INSERT INTO orders (user_id, creation_time)
                 VALUES (:user, NOW())"
            );
            $stmt->execute(['user' => $userId]);

            $orderId = (int)$this->db->lastInsertId();


            $stmtItem = $this->db->prepare(
                "INSERT INTO orderitems (order_id, product_id)
                 VALUES (:order, :product)"
            );

            foreach ($items as $item) {
                $stmtItem->execute([
                    'order' => $orderId,
                    'product' => $item['id']
                ]);
            }

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM orders
             WHERE user_id = :user
             ORDER BY creation_time DESC"
        );
        $stmt->execute(['user' => $userId]);
        return $stmt->fetchAll();
    }

    public function getItems(int $orderId): array {
        $stmt = $this->db->prepare(
            "SELECT p.*
             FROM orderitems oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = :order"
        );
        $stmt->execute(['order' => $orderId]);
        return $stmt->fetchAll();
    }
}