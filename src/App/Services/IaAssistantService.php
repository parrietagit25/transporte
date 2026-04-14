<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ConfigRepository;
use App\Models\IaRepository;
use App\Models\ServiceRepository;

final class IaAssistantService
{
    public function __construct(
        private readonly IaRepository $ia,
        private readonly ServiceRepository $services,
        private readonly ConfigRepository $config,
        private readonly OpenAiClient $openai
    ) {
    }

    public function newSession(?string $ip, ?string $ua): string
    {
        return $this->ia->createSession($ip, $ua);
    }

    /**
     * @return array{reply: string, reunion_id: int|null}
     */
    public function chat(int $sesionId, string $userMessage): array
    {
        $userMessage = trim($userMessage);
        if ($userMessage === '' || mb_strlen($userMessage) > 8000) {
            throw new \InvalidArgumentException('Mensaje no válido.');
        }
        $this->ia->addMessage($sesionId, 'user', $userMessage);

        $system = $this->buildSystemPrompt();
        $history = [];
        foreach ($this->ia->messagesForOpenAi($sesionId, 28) as $row) {
            $history[] = [
                'role' => $row['rol'] === 'assistant' ? 'assistant' : 'user',
                'content' => $row['contenido'],
            ];
        }

        $tools = [$this->toolAgendarReunion()];
        $reunionId = null;
        $maxRounds = 6;

        for ($round = 0; $round < $maxRounds; $round++) {
            $resp = $this->openai->chatCompletions($history, $system, $tools);
            $choice = $resp['choices'][0] ?? null;
            if (!is_array($choice)) {
                throw new \RuntimeException('Respuesta incompleta del modelo.');
            }
            $msg = $choice['message'] ?? [];
            if (!is_array($msg)) {
                throw new \RuntimeException('Respuesta incompleta del modelo.');
            }
            $finish = (string) ($choice['finish_reason'] ?? '');

            if (!empty($msg['tool_calls']) && is_array($msg['tool_calls'])) {
                if (!array_key_exists('content', $msg)) {
                    $msg['content'] = null;
                }
                $history[] = $msg;
                foreach ($msg['tool_calls'] as $tc) {
                    if (!is_array($tc) || ($tc['type'] ?? '') !== 'function') {
                        continue;
                    }
                    $fn = $tc['function'] ?? [];
                    $name = (string) ($fn['name'] ?? '');
                    $argsRaw = (string) ($fn['arguments'] ?? '{}');
                    $toolCallId = (string) ($tc['id'] ?? '');
                    if ($name === 'agendar_reunion') {
                        $result = $this->ejecutarAgendarReunion($sesionId, $argsRaw);
                        if ($result['reunion_id'] !== null) {
                            $reunionId = $result['reunion_id'];
                        }
                        $history[] = [
                            'role' => 'tool',
                            'tool_call_id' => $toolCallId,
                            'content' => json_encode($result, JSON_UNESCAPED_UNICODE),
                        ];
                    } else {
                        $history[] = [
                            'role' => 'tool',
                            'tool_call_id' => $toolCallId,
                            'content' => json_encode(['ok' => false, 'error' => 'función desconocida'], JSON_UNESCAPED_UNICODE),
                        ];
                    }
                }
                continue;
            }

            $content = $msg['content'] ?? '';
            if (!is_string($content)) {
                $content = '';
            }
            $content = trim($content);
            if ($content === '' && $finish === 'length') {
                $content = 'I could not finish the reply; please try again with a shorter message. '
                    . 'No pude completar la respuesta; intente de nuevo con un mensaje más corto.';
            }
            if ($content === '') {
                $content = 'How else can I help with our services or scheduling a call? '
                    . '¿En qué más puedo ayudarle con nuestros servicios o una reunión?';
            }
            $this->ia->addMessage($sesionId, 'assistant', $content);
            return ['reply' => $content, 'reunion_id' => $reunionId];
        }

        $fallback = 'Too many internal steps; please send your request again. '
            . 'Hubo demasiadas operaciones internas; escriba de nuevo su solicitud, por favor.';
        $this->ia->addMessage($sesionId, 'assistant', $fallback);
        return ['reply' => $fallback, 'reunion_id' => $reunionId];
    }

    private function buildSystemPrompt(): string
    {
        $cfg = $this->config->all();
        $empresa = $cfg['empresa_nombre'] ?? 'la empresa';
        $correo = $cfg['correo'] ?? '';
        $tel = $cfg['telefono'] ?? '';
        $dir = $cfg['direccion'] ?? '';
        $lines = [];
        foreach ($this->services->findPublished() as $s) {
            $desc = strip_tags((string) ($s['descripcion'] ?? ''));
            $desc = preg_replace('/\s+/u', ' ', $desc) ?? $desc;
            if (mb_strlen($desc) > 420) {
                $desc = mb_substr($desc, 0, 417) . '…';
            }
            $lines[] = '- ' . ($s['titulo'] ?? '') . ': ' . $desc;
        }
        $catalogo = $lines !== [] ? implode("\n", $lines) : '(Sin servicios publicados en el sitio.)';

        return <<<SYS
You are the virtual assistant for {$empresa}, a heavy transport and logistics company in Colombia.

**Language (very important):** Detect the language of the user's latest message (Spanish or English only).
- If the user writes mainly in **English**, reply entirely in **clear, professional English** (including follow-up turns while they stay in English).
- If the user writes mainly in **Spanish**, reply entirely in **clear, professional Spanish**.
- If the message is clearly mixed, use the **dominant** language. Switch language when the user clearly switches language in a new message.

Use only the service catalog and contact facts below. If something is unknown, say a sales advisor can confirm by email or phone (in the user's current language).

Official contact: email {$correo}, phone {$tel}. Address: {$dir}.

Service catalog (website summaries; titles may be in Spanish—if the user is in English, explain services accurately in English):
{$catalogo}

Guide visitors on these services. For scheduling a video call or meeting (virtual or in-person) with sales, collect full name, email, phone, desired date and time (use America/Bogota unless the user specifies otherwise), and a short reason. When all details are confirmed, call the `agendar_reunion` function **once** per request. After a successful booking, confirm briefly in the user's language.

Do not invent legal clearances or unpublished fixed prices.
SYS;
    }

    /** @return array<string, mixed> */
    private function toolAgendarReunion(): array
    {
        return [
            'type' => 'function',
            'function' => [
                'name' => 'agendar_reunion',
                'description' => 'Save a meeting or video-call request with the sales team once the user has confirmed all details. (Guardar solicitud de reunión/videollamada con comercial.)',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'nombre_contacto' => ['type' => 'string', 'description' => 'Full name / nombre completo'],
                        'email' => ['type' => 'string', 'description' => 'Email address / correo electrónico'],
                        'telefono' => ['type' => 'string', 'description' => 'Phone with country code / teléfono con indicativo'],
                        'fecha_hora_iso' => [
                            'type' => 'string',
                            'description' => 'Date and time in ISO 8601 (e.g. 2026-04-15T15:00:00-05:00). Default timezone America/Bogota unless the user specifies another.',
                        ],
                        'motivo' => ['type' => 'string', 'description' => 'Reason or topics for the meeting / motivo o temas de la reunión'],
                    ],
                    'required' => ['nombre_contacto', 'email', 'telefono', 'fecha_hora_iso', 'motivo'],
                ],
            ],
        ];
    }

    /**
     * @return array{ok: bool, reunion_id: int|null, error?: string}
     */
    private function ejecutarAgendarReunion(int $sesionId, string $argsJson): array
    {
        try {
            $args = json_decode($argsJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return ['ok' => false, 'reunion_id' => null, 'error' => 'Invalid JSON / JSON inválido'];
        }
        if (!is_array($args)) {
            return ['ok' => false, 'reunion_id' => null, 'error' => 'Invalid arguments / argumentos inválidos'];
        }
        $nom = trim((string) ($args['nombre_contacto'] ?? ''));
        $em = trim((string) ($args['email'] ?? ''));
        $tel = trim((string) ($args['telefono'] ?? ''));
        $iso = trim((string) ($args['fecha_hora_iso'] ?? ''));
        $mot = trim((string) ($args['motivo'] ?? ''));
        if ($nom === '' || $em === '' || $tel === '' || $iso === '' || $mot === '') {
            return ['ok' => false, 'reunion_id' => null, 'error' => 'Missing required fields / faltan campos obligatorios'];
        }
        if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'reunion_id' => null, 'error' => 'Invalid email / correo inválido'];
        }
        try {
            $dt = new \DateTimeImmutable($iso);
        } catch (\Exception) {
            return ['ok' => false, 'reunion_id' => null, 'error' => 'Invalid date or time / fecha u hora no válida'];
        }
        $mysql = $dt->setTimezone(new \DateTimeZone('America/Bogota'))->format('Y-m-d H:i:s');
        $id = $this->ia->insertReunion($sesionId, $nom, $em, $tel, $mysql, $mot);
        return ['ok' => true, 'reunion_id' => $id];
    }
}
