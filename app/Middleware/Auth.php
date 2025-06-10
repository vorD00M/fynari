<?php

namespace Fylari\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
    private string $secret = 'your_super_secret_key';
    private string $issuer = 'fylari_crm';

    public function verify(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!str_starts_with($header, 'Bearer ')) {
            $this->deny('Missing token');
        }

        $jwt = trim(str_replace('Bearer', '', $header));

        try {
            $decoded = JWT::decode($jwt, new Key($this->secret, 'HS256'));

            // Проверим issuer и срок действия
            if ($decoded->iss !== $this->issuer) {
                $this->deny('Invalid issuer');
            }

            if ($decoded->exp < time()) {
                $this->deny('Token expired');
            }

            // ✅ Токен валиден, можно сохранить пользователя
            $_SERVER['user_id'] = $decoded->sub;

        } catch (\Exception $e) {
            $this->deny($e->getMessage());
        }
    }

    private function deny(string $reason): void
    {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized', 'reason' => $reason]);
        exit;
    }
    public function generateToken(int $userId): string
    {
        $payload = [
            'iss' => $this->issuer,
            'iat' => time(),
            'exp' => time() + 3600 * 24, // 24 часа
            'sub' => $userId
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }
}
