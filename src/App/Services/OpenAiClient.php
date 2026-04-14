<?php

declare(strict_types=1);

namespace App\Services;

final class OpenAiClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model = 'gpt-4o-mini'
    ) {
    }

    /**
     * @param list<array{role:string,content:mixed}> $messages
     * @param list<array<string,mixed>>|null $tools
     * @return array<string, mixed>
     */
    public function chatCompletions(array $messages, ?string $systemPrompt, ?array $tools = null): array
    {
        $payload = [
            'model' => $this->model,
            'messages' => [],
        ];
        if ($systemPrompt !== null && $systemPrompt !== '') {
            $payload['messages'][] = ['role' => 'system', 'content' => $systemPrompt];
        }
        foreach ($messages as $m) {
            $payload['messages'][] = $m;
        }
        if ($tools !== null && $tools !== []) {
            $payload['tools'] = $tools;
            $payload['tool_choice'] = 'auto';
        }
        return $this->postJson('https://api.openai.com/v1/chat/completions', $payload);
    }

    /** @param array<string, mixed> $body */
    private function postJson(string $url, array $body): array
    {
        $ch = curl_init($url);
        if ($ch === false) {
            throw new \RuntimeException('No se pudo iniciar cURL.');
        }
        $json = json_encode($body, JSON_THROW_ON_ERROR);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 120,
        ]);
        $raw = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($raw === false) {
            throw new \RuntimeException('Error de red con OpenAI: ' . $err);
        }
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            throw new \RuntimeException('Respuesta OpenAI no válida.');
        }
        if ($code >= 400) {
            $msg = (string) ($data['error']['message'] ?? $raw);
            throw new \RuntimeException('OpenAI (' . $code . '): ' . $msg);
        }
        return $data;
    }
}
