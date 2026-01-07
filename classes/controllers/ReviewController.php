<?php

require_once __DIR__ . '/../entities/Review.php';

class ReviewController {
    private Review $reviewModel;

    public function __construct() {
        session_start();
        $this->reviewModel = new Review();
    }

    private function userId(): int {
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }
        return (int)$_SESSION['user']['id'];
    }

    public function add(array $data): string {
        if (
            empty($data['product_id']) ||
            empty($data['rating'])
        ) {
            return "Rating is verplicht.";
        }

        try {
            $this->reviewModel->create(
                $this->userId(),
                (int)$data['product_id'],
                (int)$data['rating'],
                $data['title'] ?? null,
                $data['comment'] ?? null
            );
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return "success";
    }

    public function getByProduct(int $productId): array {
        return $this->reviewModel->getByProduct($productId);
    }
}