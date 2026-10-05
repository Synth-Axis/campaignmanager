<?php

class Auth
{
    public static function check(bool $json = false): bool
    {
        if (!empty($_SESSION['user_id'])) return true;
        if (!empty($_COOKIE['remember_token'])) {
            $user = (new Users())->getUserByRememberToken($_COOKIE['remember_token']);
            if ($user) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user'] = $user;
                return true;
            }
        }
        if ($json) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['erro' => 'A sessão terminou. Inicie sessão novamente.']);
        } else {
            header('Location: /login');
        }
        return false;
    }
}
