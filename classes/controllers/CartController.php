<?php

require_once __DIR__ . '/../entities/Cart.php';
require_once __DIR__ . '/../entities/CartItem.php';

class CartController {
    private Cart $cartModel;
    private CartItem $cartItemModel;

    public function __construct() {
        // Don't call session_start() here - let the calling code handle it
        $this->cartModel = new Cart();
        $this->cartItemModel = new CartItem();
    }

    private function userId(): int {
        if (!isset($_SESSION['user'])) {
            throw new Exception('User not logged in');
        }
        return (int)$_SESSION['user']['id'];
    }

    public function addProduct(int $productId): void {
        $cartId = $this->cartModel->getOrCreateByUser($this->userId());
        $this->cartItemModel->add($cartId, $productId);
    }

    public function getCartItems(): array {
        $cartId = $this->cartModel->getOrCreateByUser($this->userId());
        return $this->cartItemModel->getItems($cartId);
    }

    public function removeItem(int $cartItemId): void {
        $this->cartItemModel->remove($cartItemId);
    }
}