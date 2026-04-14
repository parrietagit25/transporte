<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/bootstrap.php';

use App\Models\ConfigRepository;
use App\Models\IaRepository;
use App\Models\ServiceRepository;
use App\Services\ElevenLabsClient;
use App\Services\IaAssistantService;
use App\Services\OpenAiClient;

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
    exit;
}

$body = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'JSON inválido'], JSON_UNESCAPED_UNICODE);
    exit;
}

$csrf = $body['_csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
if (!verify_csrf_token(is_string($csrf) ? $csrf : null)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Token CSRF inválido. Recargue la página.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$action = (string) ($body['action'] ?? '');
$openaiKey = trim((string) app_config('openai_api_key', ''));
$openaiModel = trim((string) app_config('openai_model', 'gpt-4o-mini')) ?: 'gpt-4o-mini';

function ia_json_response(array $payload, int $code = 200): never
{
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    match ($action) {
        'session' => ia_handle_session($body),
        'chat' => ia_handle_chat($body, $openaiKey, $openaiModel),
        'tts' => ia_handle_tts($body),
        default => ia_json_response(['ok' => false, 'error' => 'Acción no reconocida.'], 400),
    };
} catch (\Throwable $e) {
    ia_json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}

/** @param array<string, mixed> $body */
function ia_handle_session(array $body): never
{
    $repo = new IaRepository();
    $token = $repo->createSession(
        isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : null,
        isset($_SERVER['HTTP_USER_AGENT']) ? (string) $_SERVER['HTTP_USER_AGENT'] : null
    );
    ia_json_response(['ok' => true, 'session_token' => $token]);
}

/**
 * @param array<string, mixed> $body
 */
function ia_handle_chat(array $body, string $openaiKey, string $openaiModel): never
{
    if ($openaiKey === '') {
        ia_json_response(['ok' => false, 'error' => 'Falta openai_api_key en config/secrets.php'], 503);
    }
    $sessionToken = trim((string) ($body['session_token'] ?? ''));
    $message = trim((string) ($body['message'] ?? ''));
    $repo = new IaRepository();
    $sid = $repo->findSessionIdByToken($sessionToken);
    if ($sid === null) {
        ia_json_response(['ok' => false, 'error' => 'Sesión no válida. Inicie de nuevo.'], 400);
    }
    $svc = new IaAssistantService(
        $repo,
        new ServiceRepository(),
        new ConfigRepository(),
        new OpenAiClient($openaiKey, $openaiModel)
    );
    $out = $svc->chat($sid, $message);
    ia_json_response([
        'ok' => true,
        'reply' => $out['reply'],
        'reunion_id' => $out['reunion_id'],
    ]);
}

/** @param array<string, mixed> $body */
function ia_handle_tts(array $body): never
{
    $key = trim((string) app_config('elevenlabs_api_key', ''));
    $voice = trim((string) app_config('elevenlabs_voice_id', ''));
    if ($key === '' || $voice === '') {
        ia_json_response(['ok' => false, 'error' => 'Configure elevenlabs_api_key y elevenlabs_voice_id en secrets.'], 503);
    }
    $text = trim((string) ($body['text'] ?? ''));
    if ($text === '') {
        ia_json_response(['ok' => false, 'error' => 'Texto vacío.'], 400);
    }
    $client = new ElevenLabsClient($key, $voice);
    $audio = $client->textToSpeech($text);
    ia_json_response([
        'ok' => true,
        'audio_base64' => base64_encode($audio),
        'mime' => 'audio/mpeg',
    ]);
}
