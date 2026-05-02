<?php
require_once 'verificar_jwt.php';
header('Content-Type: application/json');

$datosUsuario = verificarToken();

echo json_encode([
    'mensaje' => 'Bienvenido al area protegida!',
    'usuario' => $datosUsuario['email'],
    'rol' => $datosUsuario['rol'],
    'accedido' => date('Y-m-d H:i:s'),
    'datos_secretos' => [
        'info1' => 'Este es el contenido secreto solo para usuarios autenticados',
        'info2' => 'Solo puedes ver esto porque tienes un JWT valido!',
    ],
]);
?>
