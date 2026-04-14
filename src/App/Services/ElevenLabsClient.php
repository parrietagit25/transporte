<?php

declare(strict_types=1);

namespace App\Services;

final class ElevenLabsClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $voiceId
    ) {
    }

    public function textToSpeech(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            throw new \InvalidArgumentException('Texto vacío.');
        }
        if (mb_strlen($text) > 4000) {
            $text = mb_substr($text, 0, 4000);
        }
        if ($this->voiceId === '') {
            throw new \RuntimeException('Configure elevenlabs_voice_id en config/secrets.php');
        }
        $url = 'https://api.elevenlabs.io/v1/text-to-speech/' . rawurlencode($this->voiceId);
        $ch = curl_init($url);
        if ($ch === false) {
            throw new \RuntimeException('No se pudo iniciar cURL.');
        }
        $body = json_encode([
            'text' => $text,
            'model_id' => 'eleven_multilingual_v2',
        ], JSON_THROW_ON_ERROR);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'xi-api-key: ' . $this->apiKey,
                'Accept: audio/mpeg',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 120,
        ]);
        $raw = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($raw === false || $code >= 400) {
            $hint = '';
            if (is_string($raw) && $raw !== '' && str_starts_with(trim($raw), '{')) {
                $j = json_decode($raw, true);
                if (is_array($j)) {
                    if (isset($j['detail']['message'])) {
                        $hint = ' ' . (string) $j['detail']['message'];
                    } elseif (isset($j['detail'][0]['msg'])) {
                        $hint = ' ' . (string) $j['detail'][0]['msg'];
                    }
                }
            }
            throw new \RuntimeException('ElevenLabs TTS falló (HTTP ' . $code . ').' . $hint);
        }
        return $raw;
    }
}
