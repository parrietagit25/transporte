# Super Heavy Lift — Plataforma web (PHP + MySQL)

Aplicación corporativa con sitio público comercial, burbuja de WhatsApp configurable, panel administrativo (servicios, publicaciones, configuración, mensajes) y **módulo cotizador solo en el área privada**.

## Requisitos

- PHP **8.1+** (uso de `match`, tipos estrictos)
- MySQL **5.7+** / **MariaDB 10.3+** (recomendado MySQL 8)
- Extensión **pdo_mysql**
- Servidor web con soporte PHP (Apache con `mod_rewrite` opcional, o nginx; en Windows: **XAMPP**, **Laragon**, **WAMP**).

## Instalación en localhost

1. **Copiar el proyecto** a la carpeta del servidor, por ejemplo:
   - XAMPP: `C:\xampp\htdocs\transporte` o `C:\xampp2\htdocs\transporte`
   - Laragon: `C:\laragon\www\transporte`

2. **Crear la base de datos** en phpMyAdmin o consola MySQL:

   ```sql
   CREATE DATABASE transporte_logistica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Importar el esquema y datos demo**:

   ```bash
   mysql -u root -p transporte_logistica < database/script.sql
   ```

4. **Configurar credenciales** editando `config/database.php` (o variables de entorno `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).

5. **Document root del sitio** debe apuntar a la carpeta **`public`**:
   - URL típica: `http://localhost/transporte/public/`
   - **Sitio público:** `http://localhost/transporte/public/index.php?p=inicio`
   - **Administración:** `http://localhost/transporte/public/admin/index.php`

   En Laragon/XAMPP puede crear un virtual host cuyo `DocumentRoot` sea `.../transporte/public` y usar un dominio tipo `http://super.test`.

6. **Permisos de escritura** (Linux/macOS) en `public/uploads` para subida de imágenes:

   ```bash
   chmod -R 775 public/uploads
   ```

## Acceso al panel

- URL: `/public/admin/index.php` (o `/admin/index.php` si el vhost apunta a `public`).
- Usuario: **`admin`**
- Contraseña: **`Admin2024!`**

Cambie la contraseña desde **Perfil** en el panel.

## Integración del logo propio

1. Entre al panel → **Configuración**.
2. Suba el archivo en **“Subir logo”** (PNG/JPG/WebP). El sistema guarda la ruta en `configuracion_web.logo_path` y el sitio lo usa en **navbar**, **footer**, **pantalla de login** y **barra lateral del panel**.
3. También puede colocar manualmente un archivo en `public/uploads/branding/` y pegar la ruta en la base de datos (`/uploads/branding/archivo.ext`), o usar una URL absoluta si lo prefiere.

## Mapa en contacto (opcional)

En **Configuración** puede pegar la URL de un iframe de Google Maps (“Compartir → Insertar un mapa”) en **Mapa embebido**. Si lo deja vacío, la página de contacto usa un mapa generado a partir del campo **Dirección**.

## Actualización desde una base ya importada

Si ya tenía datos y solo falta la clave `mapa_embed_url`, ejecute en MySQL:

```sql
INSERT INTO configuracion_web (clave, valor) VALUES ('mapa_embed_url', '')
ON DUPLICATE KEY UPDATE valor = VALUES(valor);
```

## Estructura principal

- `bootstrap.php` — arranque, sesión, autoload, PDO.
- `config/` — aplicación y base de datos.
- `core/` — helpers, vistas, PDO, autenticación, subida segura.
- `src/App/Models/` — repositorios (consultas preparadas).
- `src/App/Controllers/` — controladores público y admin.
- `views/public/` y `views/admin/` — plantillas.
- `public/` — punto de entrada web (`index.php`, `admin/`, `assets/`, `uploads/`).
- `database/script.sql` — tablas e inserts demo.

## Tablas MySQL

`usuarios_admin`, `servicios`, `publicaciones`, `cotizaciones`, `configuracion_web`, `contactos_recibidos`.

Si su base se creó **antes** de los campos de montos en cotizaciones (`subtotal_sin_iva`, `otros_cargos`, `iva_pct`, `iva_monto`, `total`), ejecute una vez: `database/migration_cotizaciones_montos.sql`.

## Seguridad (resumen)

- PDO con consultas preparadas.
- Contraseñas con `password_hash` / `password_verify`.
- Sesiones HTTP-only; regeneración de ID al iniciar sesión.
- CSRF en formularios del panel y contacto público.
- Validación de subida de imágenes (MIME, tamaño, nombre aleatorio).
- `.htaccess` en `public/uploads` para denegar ejecución de PHP (Apache).

## Desarrollo posterior

- Añadir nuevas páginas públicas extendiendo `public/index.php` y `PublicSiteController`.
- Nuevos módulos admin: nuevas rutas en `AdminPanelController` y vistas en `views/admin/`.
