<?php

function view_path(string $name): string
{
    return APP_ROOT . '/views/' . $name;
}

function asset_url(string $path): string
{
    $file = APP_ROOT . '/' . $path;
    return '/' . $path . (is_file($file) ? '?v=' . filemtime($file) : '');
}

function page_scripts(): array
{
    return $GLOBALS['page_scripts'] ?? [];
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) return null;
    if (empty($_SESSION['user'])) {
        $_SESSION['user'] = (new Users())->findUserById($_SESSION['user_id']);
    }
    return $_SESSION['user'] ?: null;
}

function generateCSRFToken(): void
{
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function retainFormData($value): string
{
    return htmlspecialchars(strip_tags(trim($value)));
}

function send_email($to, $subject, $body): bool
{
    return (new EmailService())->send($to, $subject, $body);
}
