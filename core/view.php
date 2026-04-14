<?php

declare(strict_types=1);

/**
 * @param array<string, mixed> $data
 */
function render_public(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $config = $data['config'] ?? [];
    ob_start();
    require BASE_PATH . '/views/public/' . $view . '.php';
    $content = (string) ob_get_clean();
    require BASE_PATH . '/views/public/layout.php';
}

/**
 * @param array<string, mixed> $data
 */
function render_admin(string $view, array $data = [], bool $guest = false): void
{
    extract($data, EXTR_SKIP);
    ob_start();
    require BASE_PATH . '/views/admin/' . $view . '.php';
    $content = (string) ob_get_clean();
    if ($guest) {
        require BASE_PATH . '/views/admin/layout_guest.php';
    } else {
        require BASE_PATH . '/views/admin/layout.php';
    }
}
