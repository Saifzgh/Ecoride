<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler {
    private $secret;

    public function __construct() {
        $config = require __DIR__ . '/../config/jwt.php';
        $this->secret = $config['secret'];
    }

    public function generateToken($user) {
        $payload = [
            "iss" => "ecoride",
            "iat" => time(),
            "exp" => time() + (60 * 60), // Expire dans 1 heure
            "user" => [
                "id" => $user['id'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function verifyToken($token) {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (Exception $e) {
            return null;
        }
    }
}
?>
