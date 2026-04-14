<?php

declare(strict_types=1);

function app_config(string $key, mixed $default = null): mixed
{
    static $cfg;
    if ($cfg === null) {
        $cfg = require BASE_PATH . '/config/app.php';
    }
    return $cfg[$key] ?? $default;
}

function public_url(string $path = ''): string
{
    $base = rtrim((string) app_config('public_url', ''), '/');
    $path = ltrim($path, '/');
    return $path === '' ? $base : $base . '/' . $path;
}

function asset_url(string $path): string
{
    return public_url('assets/' . ltrim($path, '/'));
}

/** @param array<string, scalar|null> $query */
function admin_url(array $query = []): string
{
    $q = http_build_query($query);
    return public_url('admin/index.php' . ($q !== '' ? '?' . $q : ''));
}

function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf_token(?string $token): bool
{
    return is_string($token)
        && $token !== ''
        && isset($_SESSION['_csrf'])
        && hash_equals($_SESSION['_csrf'], $token);
}

function verify_csrf(): bool
{
    $t = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return verify_csrf_token(is_string($t) ? $t : null);
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }
    $m = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return is_string($m) ? $m : null;
}

/** Formato moneda COP para panel (ej. $ 12.345.678,00). */
function format_cop(?float $amount): string
{
    if ($amount === null) {
        return '—';
    }
    return '$ ' . number_format($amount, 2, ',', '.');
}

/** Interpreta montos desde input type=number o texto con coma decimal. */
function parse_money_input(mixed $value): ?float
{
    if ($value === null || $value === '') {
        return null;
    }
    if (is_numeric($value)) {
        return round((float) $value, 2);
    }
    $s = str_replace([' ', '$', 'COP'], '', trim((string) $value));
    $s = str_replace(',', '.', $s);
    if ($s === '' || !is_numeric($s)) {
        return null;
    }
    return round((float) $s, 2);
}

function slugify(string $text): string
{
    $conv = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    $text = $conv !== false ? $conv : $text;
    $text = preg_replace('~[^\pL\d]+~u', '-', $text) ?? '';
    $text = trim((string) $text, '-');
    $text = strtolower((string) $text);
    return $text !== '' ? $text : 'item';
}
