<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/bootstrap.php';

use App\Controllers\AdminPanelController;
use App\Models\ConfigRepository;
use App\Models\ContactRepository;
use App\Models\PostRepository;
use App\Models\IaRepository;
use App\Models\QuoteRepository;
use App\Models\ServiceRepository;
use App\Models\UserRepository;

$route = $_GET['route'] ?? 'dashboard';

$panel = new AdminPanelController(
    new ConfigRepository(),
    new ServiceRepository(),
    new PostRepository(),
    new QuoteRepository(),
    new ContactRepository(),
    new UserRepository(),
    new IaRepository()
);

$panel->handle($route);
