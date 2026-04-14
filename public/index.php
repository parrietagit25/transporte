<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Controllers\PublicSiteController;
use App\Models\ConfigRepository;
use App\Models\ContactRepository;
use App\Models\PostRepository;
use App\Models\ServiceRepository;

$controller = new PublicSiteController(
    new ConfigRepository(),
    new ServiceRepository(),
    new PostRepository(),
    new ContactRepository()
);

$page = $_GET['p'] ?? 'inicio';

if ($page === 'contacto' && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $controller->contactPost();
    exit;
}

if ($page === 'servicio') {
    $slug = trim((string) ($_GET['slug'] ?? ''));
    $slug === '' ? $controller->notFound() : $controller->serviceDetail($slug);
} elseif ($page === 'publicacion') {
    $slug = trim((string) ($_GET['slug'] ?? ''));
    $slug === '' ? $controller->notFound() : $controller->postDetail($slug);
} else {
    match ($page) {
        'inicio' => $controller->home(),
        'servicios' => $controller->services(),
        'publicaciones' => $controller->posts(),
        'asistente' => $controller->assistant(),
        'contacto' => $controller->contactGet(),
        'gracias' => $controller->thanks(),
        default => $controller->notFound(),
    };
}
