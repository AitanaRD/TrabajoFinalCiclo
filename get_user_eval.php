<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if (isset($_COOKIE['usuario_id'])) {
    $usuario = $_COOKIE['usuario_id'];
    echo json_encode(array("usuario" => $usuario));
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Error: Usuario no autenticado"));
}
?>