<?php

require_once __DIR__ . '/../entities/User.php';

class AuthController {
    private User $userModel;

    public function __construct() {
        session_start();
        $this->userModel = new User();
    }

    public function register(array $data): string {
        if (
            empty($data['first_name']) ||
            empty($data['last_name']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            return "Alle velden zijn verplicht.";
        }

        if ($this->userModel->findByEmail($data['email'])) {
            return "Dit e-mailadres is al in gebruik.";
        }

        $this->userModel->create(
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password']
        );

        return "Registratie geslaagd!";
    }

    public function login(array $data): string {
        $user = $this->userModel->findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return "Ongeldige login.";
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin']
        ];

        return "success";
    }

    public function logout(): void {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}