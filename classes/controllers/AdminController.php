<?php

require_once __DIR__ . '/../entities/Product.php';
require_once __DIR__ . '/../entities/Tag.php';

class AdminController {
    private Product $productModel;
    private Tag $tagModel;

    public function __construct() {
        session_start();

        if (
            !isset($_SESSION['user']) ||
            !$_SESSION['user']['is_admin']
        ) {
            http_response_code(403);
            exit("Geen toegang");
        }

        $this->productModel = new Product();
        $this->tagModel = new Tag();
    }

    public function getProducts(): array {
        return $this->productModel->getAll();
    }

    public function getProduct(int $id): ?array {
        return $this->productModel->getById($id);
    }

    public function getTags(): array {
        return $this->tagModel->getAll();
    }

    public function getProductTags(int $productId): array {
        return $this->tagModel->getByProduct($productId);
    }

    public function createTag(string $tag): int {
        return $this->tagModel->create($tag);
    }

    public function updateTag(int $id, string $tag): void {
        $this->tagModel->update($id, $tag);
    }

    public function deleteTag(int $id): void {
        $this->tagModel->delete($id);
    }

    public function saveProduct(array $data): void {
        if (empty($data['name']) || empty($data['price'])) {
            throw new Exception("Naam en prijs zijn verplicht");
        }

        if (empty($data['id'])) {
            $productId = $this->productModel->create(
                $data['name'],
                $data['description'] ?? null,
                (int)$data['price']
            );
        } else {
            $this->productModel->update(
                (int)$data['id'],
                $data['name'],
                $data['description'] ?? null,
                (int)$data['price']
            );
            $productId = (int)$data['id'];
        }

        $this->tagModel->setForProduct(
            $productId,
            $data['tags'] ?? []
        );
    }

    public function deleteProduct(int $id): void {
        $this->productModel->delete($id);
    }
}