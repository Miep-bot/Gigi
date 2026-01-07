<?php

require_once __DIR__ . '/../entities/Cart.php';
require_once __DIR__ . '/../entities/CartItem.php';

class CartController {
    private Cart $cartModel;
    private CartItem $cartItemModel;

    public function __construct() {
        session_start();
        $this->cartModel = new Cart();
        $this->cartItemModel = new CartItem();
    }

    private function userId(): int {
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit;
        }
        return (int)$_SESSION['user']['id'];
    }

    public function addProduct(int $productId): void {
        $cartId = $this->cartModel->getOrCreateByUser($this->userId());
        $this->cartItemModel->add($cartId, $productId);
        header("Location: cart.php");
        exit;
    }

    public function getCartItems(): array {
        $cartId = $this->cartModel->getOrCreateByUser($this->userId());
        return $this->cartItemModel->getItems($cartId);
    }

    public function removeItem(int $cartItemId): void {
        $this->cartItemModel->remove($cartItemId);
        header("Location: cart.php");
        exit;
    }
}