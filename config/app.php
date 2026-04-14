<?php

declare(strict_types=1);

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
$dir = dirname($script);
if (preg_match('#/admin$#', $dir)) {
    $dir = dirname($dir);
}
$basePath = rtrim($dir === '/' ? '' : $dir, '/');

$cfg = [
    'name' => 'Super Heavy Lift',
    'base_url' => $scheme . '://' . $host . $basePath,
    'public_url' => $scheme . '://' . $host . $basePath,
    'timezone' => 'America/Bogota',
    'upload_max_bytes' => 3 * 1024 * 1024,
    'allowed_image_mimes' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
];

$secretsFile = __DIR__ . '/secrets.php';
if (is_file($secretsFile)) {
    /** @var array<string, mixed> $extra */
    $extra = require $secretsFile;
    foreach ($extra as $k => $v) {
        $cfg[$k] = $v;
    }
}

/** Si falta en secrets.php, se pueden definir variables de entorno (Apache SetEnv, sistema, etc.). */
$envFallbacks = [
    'openai_api_key' => 'OPENAI_API_KEY',
    'openai_model' => 'OPENAI_MODEL',
    'elevenlabs_api_key' => 'ELEVENLABS_API_KEY',
    'elevenlabs_voice_id' => 'ELEVENLABS_VOICE_ID',
];
foreach ($envFallbacks as $cfgKey => $envName) {
    $cur = trim((string) ($cfg[$cfgKey] ?? ''));
    if ($cur !== '') {
        continue;
    }
    $v = getenv($envName);
    if (is_string($v) && $v !== '') {
        $cfg[$cfgKey] = $v;
    }
}

return $cfg;
