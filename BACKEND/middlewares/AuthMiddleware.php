<?php
require_once __DIR__ . '/../helpers/JwtHandler.php';

class AuthMiddleware {
    public static function validateToken() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Token manquant"]);
            exit;
        }

        $token = str_replace("Bearer ", "", $headers['Authorization']);
        $jwt = new JwtHandler();
        $decoded = $jwt->verifyToken($token);

        if (!$decoded) {
            http_response_code(403);
            echo json_encode(["error" => "Token invalide"]);
            exit;
        }

        return $decoded->user;
    }
}
?>
