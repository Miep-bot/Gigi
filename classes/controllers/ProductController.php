<?php

require_once __DIR__ . '/../entities/Product.php';
require_once __DIR__ . '/../entities/Tag.php';

class ProductController {
    private Product $productModel;
    private Tag $tagModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->tagModel = new Tag();
    }

    public function getProducts(?int $tagId = null): array {
        if ($tagId) {
            return $this->productModel->getByTag($tagId);
        }

        return $this->productModel->getAll();
    }

    public function getTags(): array {
        return $this->tagModel->getAll();
    }
}