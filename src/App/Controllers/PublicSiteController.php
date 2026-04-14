<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ConfigRepository;
use App\Models\ContactRepository;
use App\Models\PostRepository;
use App\Models\ServiceRepository;

final class PublicSiteController
{
    public function __construct(
        private readonly ConfigRepository $config,
        private readonly ServiceRepository $services,
        private readonly PostRepository $posts,
        private readonly ContactRepository $contacts,
    ) {
    }

    /** @return array<string, string> */
    public function configArray(): array
    {
        return $this->config->all();
    }

    public function home(): void
    {
        $config = $this->configArray();
        $servicios = $this->services->findPublished();
        $destacadas = $this->posts->findFeatured(4);
        render_public('home', [
            'config' => $config,
            'servicios' => $servicios,
            'destacadas' => $destacadas,
            'pageTitle' => 'Inicio',
        ]);
    }

    public function services(): void
    {
        $config = $this->configArray();
        $servicios = $this->services->findPublished();
        render_public('services', [
            'config' => $config,
            'servicios' => $servicios,
            'pageTitle' => 'Servicios',
        ]);
    }

    public function serviceDetail(string $slug): void
    {
        $config = $this->configArray();
        $s = $this->services->findBySlug($slug);
        if (!$s) {
            http_response_code(404);
            render_public('404', ['config' => $config, 'pageTitle' => 'No encontrado']);
            return;
        }
        render_public('service_detail', [
            'config' => $config,
            'servicio' => $s,
            'pageTitle' => $s['titulo'],
        ]);
    }

    public function posts(): void
    {
        $config = $this->configArray();
        $lista = $this->posts->findPublished(50);
        render_public('posts', [
            'config' => $config,
            'publicaciones' => $lista,
            'pageTitle' => 'Proyectos y novedades',
        ]);
    }

    public function postDetail(string $slug): void
    {
        $config = $this->configArray();
        $p = $this->posts->findBySlug($slug);
        if (!$p) {
            http_response_code(404);
            render_public('404', ['config' => $config, 'pageTitle' => 'No encontrado']);
            return;
        }
        render_public('post_detail', [
            'config' => $config,
            'publicacion' => $p,
            'pageTitle' => $p['titulo'],
        ]);
    }

    public function assistant(): void
    {
        render_public('assistant', [
            'config' => $this->configArray(),
            'pageTitle' => 'Asistente virtual',
        ]);
    }

    public function contactGet(): void
    {
        $config = $this->configArray();
        render_public('contact', [
            'config' => $config,
            'pageTitle' => 'Contacto',
            'errors' => [],
            'old' => [],
        ]);
    }

    public function contactPost(): void
    {
        if (!verify_csrf()) {
            http_response_code(400);
            exit('Solicitud no válida.');
        }
        $config = $this->configArray();
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $telefono = trim((string) ($_POST['telefono'] ?? ''));
        $asunto = trim((string) ($_POST['asunto'] ?? ''));
        $mensaje = trim((string) ($_POST['mensaje'] ?? ''));
        $errors = [];
        if ($nombre === '' || mb_strlen($nombre) < 3) {
            $errors[] = 'Indique un nombre válido.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Indique un correo electrónico válido.';
        }
        if ($mensaje === '' || mb_strlen($mensaje) < 10) {
            $errors[] = 'El mensaje debe tener al menos 10 caracteres.';
        }
        if ($errors !== []) {
            render_public('contact', [
                'config' => $config,
                'pageTitle' => 'Contacto',
                'errors' => $errors,
                'old' => compact('nombre', 'email', 'telefono', 'asunto', 'mensaje'),
            ]);
            return;
        }
        $this->contacts->create([
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'asunto' => $asunto,
            'mensaje' => $mensaje,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
        redirect(public_url('index.php?p=gracias'));
    }

    public function thanks(): void
    {
        render_public('thanks', [
            'config' => $this->configArray(),
            'pageTitle' => 'Gracias',
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);
        render_public('404', [
            'config' => $this->configArray(),
            'pageTitle' => 'No encontrado',
        ]);
    }
}
