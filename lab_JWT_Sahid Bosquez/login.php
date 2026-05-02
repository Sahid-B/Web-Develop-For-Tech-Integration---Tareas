<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
use Firebase\JWT\JWT;

header('Content-Type: application/json');

// Leer datos del Body en Postman
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan email o password.']);
    exit();
}

try {
    // 1. Conexión a la base de datos con PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. RETO AVANZADO: Buscar el usuario en la tabla MySQL
    $stmt = $pdo->prepare("SELECT id, email, password, rol FROM usuarios WHERE email = :email");
    $stmt->execute(['email' => $data['email']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Verificar si el usuario existe y la contraseña coincide
    if ($usuario && $data['password'] === $usuario['password']) {
        
        // Crear el Payload con los datos de la DB
        $payload = [
            'iss' => 'taller_jwt',
            'iat' => time(),
            'exp' => time() + JWT_EXPIRATION,
            'user_id' => $usuario['id'],
            'email' => $usuario['email'],
            'rol' => $usuario['rol']
        ];

        // Generar el Token
        $token = JWT::encode($payload, JWT_SECRET, JWT_ALGORITHM);

        echo json_encode([
            'mensaje' => '¡Login exitoso desde la Base de Datos!',
            'token' => $token,
            'detalles' => [
                'usuario' => $usuario['email'],
                'rol_asignado' => $usuario['rol']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Credenciales incorrectas. Verifique sus datos en la DB.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la DB: ' . $e->getMessage()]);
}
?>