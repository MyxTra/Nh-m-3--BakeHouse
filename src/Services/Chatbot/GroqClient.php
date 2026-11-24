<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Core\Env;
use RuntimeException;

final class GroqClient
{
    private string $endpoint;
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->endpoint = rtrim((string) Env::get('GROQ_ENDPOINT', 'https://api.groq.com/openai/v1/chat/completions'), '/');
        $this->apiKey = trim((string) Env::get('GROQ_API_KEY', ''));
        $this->model = trim((string) Env::get('GROQ_MODEL', 'llama-3.3-70b-versatile'));
    }

    /**
     * Groq không có endpoint embedding riêng; trả [] để luồng tìm sản phẩm dùng tìm kiếm từ khóa.
     *
     * @return array<int, float>
     */
    public function embed(string $text): array
    {
        return [];
    }

    /**
     * Gọi Groq Chat Completions (chuẩn OpenAI) có hỗ trợ tool calling.
     *
     * @param array<int, array<string, mixed>> $messages
     * @param array<int, array<string, mixed>> $tools
     * @return array<string, mixed>
     */
    public function chatCompletions(array $messages, array $tools = [], float $temperature = 0.4, int $maxTokens = 1500): array
    {
        if ($this->apiKey === '') {
            throw new RuntimeException('Thiếu GROQ_API_KEY trong file .env');
        }

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ];

        if ($tools !== []) {
            $payload['tools'] = $tools;
            $payload['tool_choice'] = 'auto';
        }

        $ch = curl_init($this->endpoint);
        if ($ch === false) {
            throw new RuntimeException('Không thể khởi tạo cURL tới Groq API.');
        }

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 45,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $rawBody = curl_exec($ch);
        $curlErrno = curl_errno($ch);
        $curlError = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlErrno !== 0) {
            throw new RuntimeException('Lỗi kết nối Groq API: ' . $curlError);
        }

        $decoded = is_string($rawBody) ? json_decode($rawBody, true) : null;
        if (!is_array($decoded)) {
            throw new RuntimeException('Phản hồi từ Groq API không hợp lệ.');
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            $apiMsg = (string) ($decoded['error']['message'] ?? ('HTTP ' . $httpCode));
            throw new RuntimeException("Groq API lỗi ({$httpCode}): {$apiMsg}");
        }

        return $decoded;
    }
}