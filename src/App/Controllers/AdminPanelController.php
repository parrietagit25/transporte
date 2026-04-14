<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Upload;
use App\Models\ConfigRepository;
use App\Models\ContactRepository;
use App\Models\IaRepository;
use App\Models\PostRepository;
use App\Models\QuoteRepository;
use App\Models\ServiceRepository;
use App\Models\UserRepository;
use App\Models\QuoteMoney;

final class AdminPanelController
{
    public function __construct(
        private readonly ConfigRepository $config,
        private readonly ServiceRepository $services,
        private readonly PostRepository $posts,
        private readonly QuoteRepository $quotes,
        private readonly ContactRepository $contacts,
        private readonly UserRepository $users,
        private readonly IaRepository $ia,
    ) {
    }

    public function handle(string $route): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (!Auth::check()) {
            if ($route !== 'login') {
                redirect(admin_url(['route' => 'login']));
            }
        } elseif ($route === 'login') {
            redirect(admin_url(['route' => 'dashboard']));
        }

        if ($route === 'login') {
            if ($method === 'POST') {
                $this->processLogin();
            } else {
                render_admin('login', [
                    'pageTitle' => 'Ingreso',
                    'cfg' => $this->config->all(),
                ], true);
            }
            return;
        }

        Auth::requireLogin();

        match ($route) {
            'logout' => $this->logout(),
            'dashboard' => $this->dashboard(),
            'config' => $method === 'POST' ? $this->saveConfig() : $this->configForm(),
            'servicios' => $this->servicesList(),
            'servicio_nuevo' => $this->serviceForm(null),
            'servicio_editar' => $this->serviceForm((int) ($_GET['id'] ?? 0)),
            'servicio_guardar' => $method === 'POST' ? $this->serviceSave() : redirect(admin_url(['route' => 'servicios'])),
            'servicio_eliminar' => $method === 'POST' ? $this->serviceDelete() : redirect(admin_url(['route' => 'servicios'])),
            'publicaciones' => $this->postsList(),
            'publicacion_nueva' => $this->postForm(null),
            'publicacion_editar' => $this->postForm((int) ($_GET['id'] ?? 0)),
            'publicacion_guardar' => $method === 'POST' ? $this->postSave() : redirect(admin_url(['route' => 'publicaciones'])),
            'publicacion_eliminar' => $method === 'POST' ? $this->postDelete() : redirect(admin_url(['route' => 'publicaciones'])),
            'cotizaciones' => $this->quotesList(),
            'cotizacion' => $this->quoteView((int) ($_GET['id'] ?? 0)),
            'cotizacion_nueva' => $this->quoteForm(null),
            'cotizacion_editar' => $this->quoteForm((int) ($_GET['id'] ?? 0)),
            'cotizacion_guardar' => $method === 'POST' ? $this->quoteSave() : redirect(admin_url(['route' => 'cotizaciones'])),
            'cotizacion_eliminar' => $method === 'POST' ? $this->quoteDelete() : redirect(admin_url(['route' => 'cotizaciones'])),
            'cotizacion_estado' => $method === 'POST' ? $this->quoteEstado() : redirect(admin_url(['route' => 'cotizaciones'])),
            'contactos' => $this->contactsList(),
            'contacto_leido' => $method === 'POST' ? $this->contactRead() : redirect(admin_url(['route' => 'contactos'])),
            'demo_ia' => $this->iaDemo(),
            'perfil' => $method === 'POST' ? $this->passwordChange() : $this->profileForm(),
            default => redirect(admin_url(['route' => 'dashboard'])),
        };
    }

    private function logout(): never
    {
        Auth::logout();
        redirect(admin_url(['route' => 'login']));
    }

    private function processLogin(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token de seguridad inválido.');
            redirect(admin_url(['route' => 'login']));
        }
        $u = trim((string) ($_POST['username'] ?? ''));
        $p = (string) ($_POST['password'] ?? '');
        if (!Auth::attempt($u, $p)) {
            flash('error', 'Credenciales incorrectas.');
            redirect(admin_url(['route' => 'login']));
        }
        redirect(admin_url(['route' => 'dashboard']));
    }

    private function dashboard(): void
    {
        $cfg = $this->config->all();
        render_admin('dashboard', [
            'pageTitle' => 'Dashboard',
            'cfg' => $cfg,
            'nServicios' => count($this->services->findAllAdmin()),
            'nPosts' => count($this->posts->findAllAdmin()),
            'nQuotes' => count($this->quotes->search(null, null, 500)),
            'nUnread' => $this->contacts->unreadCount(),
        ]);
    }

    private function configForm(): void
    {
        render_admin('config', [
            'pageTitle' => 'Configuración del sitio',
            'cfg' => $this->config->all(),
        ]);
    }

    private function saveConfig(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'config']));
        }
        $keys = [
            'empresa_nombre', 'telefono', 'whatsapp_numero', 'whatsapp_mensaje', 'correo', 'direccion',
            'hero_titulo', 'hero_subtitulo', 'facebook_url', 'instagram_url', 'linkedin_url',
            'meta_description', 'hero_imagen_path', 'mapa_embed_url',
        ];
        $pairs = [];
        foreach ($keys as $k) {
            $pairs[$k] = trim((string) ($_POST[$k] ?? ''));
        }
        try {
            if (!empty($_FILES['logo']['name'])) {
                $pairs['logo_path'] = Upload::image($_FILES['logo'], 'branding');
            }
            if (!empty($_FILES['hero_imagen']['name'])) {
                $pairs['hero_imagen_path'] = Upload::image($_FILES['hero_imagen'], 'branding');
            }
        } catch (\Throwable $e) {
            flash('error', 'Archivo: ' . $e->getMessage());
            redirect(admin_url(['route' => 'config']));
        }
        $this->config->setMany($pairs);
        flash('ok', 'Configuración guardada correctamente.');
        redirect(admin_url(['route' => 'config']));
    }

    private function servicesList(): void
    {
        render_admin('services_list', [
            'pageTitle' => 'Servicios',
            'items' => $this->services->findAllAdmin(),
        ]);
    }

    private function serviceForm(?int $id): void
    {
        $row = null;
        if ($id) {
            $row = $this->services->findById($id);
            if (!$row) {
                flash('error', 'Servicio no encontrado.');
                redirect(admin_url(['route' => 'servicios']));
            }
        }
        render_admin('service_form', [
            'pageTitle' => $row ? 'Editar servicio' : 'Nuevo servicio',
            'row' => $row,
        ]);
    }

    private function serviceSave(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'servicios']));
        }
        $id = (int) ($_POST['id'] ?? 0);
        $titulo = trim((string) ($_POST['titulo'] ?? ''));
        $slug = trim((string) ($_POST['slug'] ?? ''));
        if ($slug === '') {
            $slug = slugify($titulo);
        }
        $data = [
            'titulo' => $titulo,
            'slug' => $slug,
            'descripcion' => trim((string) ($_POST['descripcion'] ?? '')),
            'beneficios' => trim((string) ($_POST['beneficios'] ?? '')),
            'imagen' => trim((string) ($_POST['imagen'] ?? '')),
            'orden' => (int) ($_POST['orden'] ?? 0),
            'activo' => isset($_POST['activo']) ? 1 : 0,
        ];
        try {
            if (!empty($_FILES['imagen_archivo']['name'])) {
                $data['imagen'] = Upload::image($_FILES['imagen_archivo'], 'servicios');
            }
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
            redirect(admin_url($id ? ['route' => 'servicio_editar', 'id' => $id] : ['route' => 'servicio_nuevo']));
        }
        if ($id > 0) {
            $this->services->update($id, $data);
            flash('ok', 'Servicio actualizado.');
        } else {
            $this->services->create($data);
            flash('ok', 'Servicio creado.');
        }
        redirect(admin_url(['route' => 'servicios']));
    }

    private function serviceDelete(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'servicios']));
        }
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->services->delete($id);
            flash('ok', 'Servicio eliminado.');
        }
        redirect(admin_url(['route' => 'servicios']));
    }

    private function postsList(): void
    {
        render_admin('posts_list', [
            'pageTitle' => 'Publicaciones',
            'items' => $this->posts->findAllAdmin(),
        ]);
    }

    private function postForm(?int $id): void
    {
        $row = null;
        if ($id) {
            $row = $this->posts->findById($id);
            if (!$row) {
                flash('error', 'Publicación no encontrada.');
                redirect(admin_url(['route' => 'publicaciones']));
            }
        }
        render_admin('post_form', [
            'pageTitle' => $row ? 'Editar publicación' : 'Nueva publicación',
            'row' => $row,
        ]);
    }

    private function postSave(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'publicaciones']));
        }
        $id = (int) ($_POST['id'] ?? 0);
        $titulo = trim((string) ($_POST['titulo'] ?? ''));
        $slug = trim((string) ($_POST['slug'] ?? ''));
        if ($slug === '') {
            $slug = slugify($titulo);
        }
        $data = [
            'titulo' => $titulo,
            'slug' => $slug,
            'resumen' => trim((string) ($_POST['resumen'] ?? '')),
            'contenido' => trim((string) ($_POST['contenido'] ?? '')),
            'imagen_destacada' => trim((string) ($_POST['imagen_destacada'] ?? '')),
            'fecha_publicacion' => trim((string) ($_POST['fecha_publicacion'] ?? date('Y-m-d'))),
            'estado' => ($_POST['estado'] ?? 'borrador') === 'publicado' ? 'publicado' : 'borrador',
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
        ];
        try {
            if (!empty($_FILES['imagen_archivo']['name'])) {
                $data['imagen_destacada'] = Upload::image($_FILES['imagen_archivo'], 'publicaciones');
            }
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
            redirect(admin_url($id ? ['route' => 'publicacion_editar', 'id' => $id] : ['route' => 'publicacion_nueva']));
        }
        if ($id > 0) {
            $this->posts->update($id, $data);
            flash('ok', 'Publicación actualizada.');
        } else {
            $this->posts->create($data);
            flash('ok', 'Publicación creada.');
        }
        redirect(admin_url(['route' => 'publicaciones']));
    }

    private function postDelete(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'publicaciones']));
        }
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->posts->delete($id);
            flash('ok', 'Publicación eliminada.');
        }
        redirect(admin_url(['route' => 'publicaciones']));
    }

    private function quotesList(): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $estado = trim((string) ($_GET['estado'] ?? ''));
        $estado = $estado !== '' ? $estado : null;
        $items = $this->quotes->search($q !== '' ? $q : null, $estado);
        render_admin('quotes_list', [
            'pageTitle' => 'Cotizador',
            'items' => $items,
            'q' => $q,
            'estado' => $estado ?? '',
        ]);
    }

    private function quoteView(int $id): void
    {
        $row = $this->quotes->findById($id);
        if (!$row) {
            flash('error', 'Cotización no encontrada.');
            redirect(admin_url(['route' => 'cotizaciones']));
        }
        render_admin('quote_detail', [
            'pageTitle' => 'Cotización #' . $id,
            'row' => $row,
        ]);
    }

    private function quoteForm(?int $id): void
    {
        $row = null;
        if ($id) {
            $row = $this->quotes->findById($id);
            if (!$row) {
                flash('error', 'Cotización no encontrada.');
                redirect(admin_url(['route' => 'cotizaciones']));
            }
        }
        render_admin('quote_form', [
            'pageTitle' => $row ? 'Editar cotización' : 'Nueva cotización',
            'row' => $row,
        ]);
    }

    private function quoteSave(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'cotizaciones']));
        }
        $id = (int) ($_POST['id'] ?? 0);
        $money = QuoteMoney::fromPost($_POST);
        $data = [
            'cliente_nombre' => trim((string) ($_POST['cliente_nombre'] ?? '')),
            'cliente_empresa' => trim((string) ($_POST['cliente_empresa'] ?? '')),
            'cliente_telefono' => trim((string) ($_POST['cliente_telefono'] ?? '')),
            'cliente_correo' => trim((string) ($_POST['cliente_correo'] ?? '')),
            'tipo_servicio' => trim((string) ($_POST['tipo_servicio'] ?? '')),
            'origen' => trim((string) ($_POST['origen'] ?? '')),
            'destino' => trim((string) ($_POST['destino'] ?? '')),
            'tipo_carga' => trim((string) ($_POST['tipo_carga'] ?? '')),
            'peso_estimado' => trim((string) ($_POST['peso_estimado'] ?? '')),
            'dimensiones' => trim((string) ($_POST['dimensiones'] ?? '')),
            'fecha_requerida' => trim((string) ($_POST['fecha_requerida'] ?? '')),
            'observaciones' => trim((string) ($_POST['observaciones'] ?? '')),
            'subtotal_sin_iva' => $money['subtotal_sin_iva'],
            'otros_cargos' => $money['otros_cargos'],
            'iva_pct' => $money['iva_pct'],
            'iva_monto' => $money['iva_monto'],
            'total' => $money['total'],
            'estado' => (string) ($_POST['estado'] ?? 'pendiente'),
        ];
        $allowed = ['pendiente', 'en_revision', 'enviada', 'aprobada', 'rechazada', 'cerrada'];
        if (!in_array($data['estado'], $allowed, true)) {
            $data['estado'] = 'pendiente';
        }
        if ($data['cliente_nombre'] === '' || $data['cliente_telefono'] === '' || $data['cliente_correo'] === '') {
            flash('error', 'Complete nombre, teléfono y correo.');
            redirect(admin_url($id ? ['route' => 'cotizacion_editar', 'id' => $id] : ['route' => 'cotizacion_nueva']));
        }
        if (!filter_var($data['cliente_correo'], FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Indique un correo electrónico válido.');
            redirect(admin_url($id ? ['route' => 'cotizacion_editar', 'id' => $id] : ['route' => 'cotizacion_nueva']));
        }
        foreach (['tipo_servicio' => 'tipo de servicio', 'origen' => 'origen', 'destino' => 'destino', 'tipo_carga' => 'tipo de carga'] as $field => $label) {
            if ($data[$field] === '') {
                flash('error', 'Complete el campo: ' . $label . '.');
                redirect(admin_url($id ? ['route' => 'cotizacion_editar', 'id' => $id] : ['route' => 'cotizacion_nueva']));
            }
        }
        if ($id > 0) {
            $this->quotes->update($id, $data);
            flash('ok', 'Cotización actualizada.');
        } else {
            $this->quotes->create($data);
            flash('ok', 'Cotización creada.');
        }
        redirect(admin_url(['route' => 'cotizaciones']));
    }

    private function quoteDelete(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'cotizaciones']));
        }
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->quotes->delete($id);
            flash('ok', 'Cotización eliminada.');
        }
        redirect(admin_url(['route' => 'cotizaciones']));
    }

    private function quoteEstado(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'cotizaciones']));
        }
        $id = (int) ($_POST['id'] ?? 0);
        $estado = (string) ($_POST['estado'] ?? 'pendiente');
        $allowed = ['pendiente', 'en_revision', 'enviada', 'aprobada', 'rechazada', 'cerrada'];
        if ($id > 0 && in_array($estado, $allowed, true)) {
            $this->quotes->updateEstado($id, $estado);
            flash('ok', 'Estado actualizado.');
        }
        redirect(admin_url(['route' => 'cotizaciones']));
    }

    private function iaDemo(): void
    {
        $sid = (int) ($_GET['sesion'] ?? 0);
        $mensajes = $sid > 0 ? $this->ia->adminMessages($sid) : [];
        render_admin('ia_demo', [
            'pageTitle' => 'Demo IA',
            'sesiones' => $this->ia->adminListSessions(60),
            'reuniones' => $this->ia->adminListReuniones(80),
            'mensajes' => $mensajes,
            'sesion_vista' => $sid,
        ]);
    }

    private function contactsList(): void
    {
        render_admin('contacts_list', [
            'pageTitle' => 'Mensajes de contacto',
            'items' => $this->contacts->findAllAdmin(),
        ]);
    }

    private function contactRead(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'contactos']));
        }
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->contacts->markRead($id);
        }
        redirect(admin_url(['route' => 'contactos']));
    }

    private function profileForm(): void
    {
        render_admin('profile', ['pageTitle' => 'Mi perfil']);
    }

    private function passwordChange(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token inválido.');
            redirect(admin_url(['route' => 'perfil']));
        }
        $p1 = (string) ($_POST['password'] ?? '');
        $p2 = (string) ($_POST['password2'] ?? '');
        if (strlen($p1) < 8 || $p1 !== $p2) {
            flash('error', 'Las contraseñas deben coincidir y tener al menos 8 caracteres.');
            redirect(admin_url(['route' => 'perfil']));
        }
        $id = Auth::id();
        if ($id) {
            $this->users->updatePassword($id, password_hash($p1, PASSWORD_DEFAULT));
            flash('ok', 'Contraseña actualizada.');
        }
        redirect(admin_url(['route' => 'perfil']));
    }
}
