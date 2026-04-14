<?php

declare(strict_types=1);

namespace App\Core;

final class Upload
{
    public static function image(array $file, string $subdir = ''): string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            throw new \InvalidArgumentException('No se recibió archivo.');
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Error al subir el archivo.');
        }
        $max = (int) app_config('upload_max_bytes', 3145728);
        if (($file['size'] ?? 0) > $max) {
            throw new \InvalidArgumentException('El archivo supera el tamaño máximo permitido.');
        }
        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            throw new \RuntimeException('Archivo inválido.');
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp) ?: '';
        $allowed = app_config('allowed_image_mimes', []);
        if (!in_array($mime, $allowed, true)) {
            throw new \InvalidArgumentException('Tipo de imagen no permitido.');
        }
        $extMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];
        $ext = $extMap[$mime] ?? 'bin';
        $dir = UPLOAD_PATH . ($subdir !== '' ? DIRECTORY_SEPARATOR . trim($subdir, '/\\') : '');
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \RuntimeException('No se pudo crear el directorio de uploads.');
        }
        $name = bin2hex(random_bytes(12)) . '.' . $ext;
        $dest = $dir . DIRECTORY_SEPARATOR . $name;
        if (!move_uploaded_file($tmp, $dest)) {
            throw new \RuntimeException('No se pudo guardar el archivo.');
        }
        $rel = UPLOAD_URL . ($subdir !== '' ? '/' . trim(str_replace('\\', '/', $subdir), '/') : '') . '/' . $name;
        return str_replace('\\', '/', $rel);
    }
}
