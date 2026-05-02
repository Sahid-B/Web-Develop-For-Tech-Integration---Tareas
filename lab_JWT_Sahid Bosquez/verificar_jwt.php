<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Función que verifica si un JWT es válido.
 * Retorna los datos del usuario si es válido, o null si algo está mal.
 */
function verificarToken() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Token no proporcionado']);
        exit();
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);

    try {
        $decoded = JWT::decode(
            $token,
            new Key(JWT_SECRET, JWT_ALGORITHM)
        );
        return (array) $decoded;
    } catch (\Firebase\JWT\ExpiredException $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Token expirado. Inicia sesion de nuevo.']);
        exit();
    } catch (\Firebase\JWT\SignatureInvalidException $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Token invalido o modificado.']);
        exit();
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Token no valido: ' . $e->getMessage()]);
        exit();
    }
}
?>
