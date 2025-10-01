<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: /web2-practical/news_app/auth/login.php');
        exit;
    }
}

function e($s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
