<?php

class AuthController {
    private PDO $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }

    public function login(string $username, string $password): bool {
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['tipo_usuario'];
            $_SESSION['username'] = $user['username'];
            return true;
        }

        return false;
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    public function isAuthenticated(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    public function getUserRole(): ?string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_role'] ?? null;
    }

    public function requireRole(string $role): void {
        if (!$this->isAuthenticated() || $this->getUserRole() !== $role) {
            $loginPath = $this->getLoginUrl();
            header('Location: ' . $loginPath);
            exit();
        }
    }

    private function getLoginUrl(): string {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/login.php';
        $publicPos = strpos($scriptName, '/public');
        if ($publicPos !== false) {
            return substr($scriptName, 0, $publicPos + strlen('/public')) . '/login.php';
        }
        return '/login.php';
    }
}
