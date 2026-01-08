<?php

require_once __DIR__ . '/../entities/Order.php';
require_once __DIR__ . '/../entities/User.php';
require_once __DIR__ . '/../entities/Cart.php';
require_once __DIR__ . '/../entities/CartItem.php';

class OrderController {
    private Order $orderModel;
    private User $userModel;
    private Cart $cartModel;
    private CartItem $cartItemModel;

    public function __construct() {
        session_start();
        $this->orderModel = new Order();
        $this->userModel = new User();
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

    public function checkout(): string {
        $userId = $this->userId();
        $user = $this->userModel->getById($userId);

        $cartId = $this->cartModel->getOrCreateByUser($userId);
        $items = $this->cartItemModel->getItems($cartId);

        if (empty($items)) {
            return "Je winkelmandje is leeg.";
        }

        $total = array_sum(array_column($items, 'price'));

        if ($user['coins'] < $total) {
            return "Onvoldoende coins.";
        }

        
        $this->orderModel->create($userId, $items);

        
        $newCoins = $user['coins'] - $total;
        $this->userModel->updateCoins($userId, $newCoins);
        if (isset($_SESSION['user'])) {
            $_SESSION['user']['coins'] = $newCoins;
        }


        foreach ($items as $item) {
            $this->cartItemModel->remove($item['cartitem_id']);
        }

        return "success";
    }

    public function getOrders(): array {
        return $this->orderModel->getByUser($this->userId());
    }

    public function getOrderItems(int $orderId): array {
        return $this->orderModel->getItems($orderId);
    }
}