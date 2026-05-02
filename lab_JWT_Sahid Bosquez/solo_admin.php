<?php
require_once 'verificar_jwt.php';
header('Content-Type: application/json');

// 1. Verificar si el token es válido usando el "guardia" que ya creamos
$datosUsuario = verificarToken();

// 2. RETO MEDIO: Validar si el rol guardado en el JWT es 'admin'
if ($datosUsuario['rol'] !== 'admin') {
    // Si el rol NO es admin, lanzamos el error 403 (Forbidden/Prohibido)
    http_response_code(403);
    echo json_encode([
        'error' => 'Acceso Prohibido',
        'mensaje' => 'Lo sentimos, esta ruta es exclusiva para administradores.',
        'tu_rol_actual' => $datosUsuario['rol']
    ]);
    exit();
}

// 3. Si el rol es 'admin', permitimos el acceso
echo json_encode([
    'mensaje' => '¡Bienvenido, Administrador!',
    'contenido_secreto' => 'Este mensaje solo lo pueden ver usuarios con nivel de Admin.',
    'usuario_autorizado' => $datosUsuario['email']
]);
?>