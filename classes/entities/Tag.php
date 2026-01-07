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
}