<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Permitir solicitudes desde el origen del cliente
    header("Access-Control-Allow-Origin: http://localhost:8101");
    // Permitir métodos GET, POST y OPTIONS
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    // Permitir los encabezados Content-Type y Authorization
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    // Permitir credenciales
    header("Access-Control-Allow-Credentials: true");
    // Enviar una respuesta vacía con el código de estado 200
    http_response_code(200);
    exit;
}

header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
require("db_connect.php");
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['usuario'], $data['calificacion'], $data['comentario'], $data['destinatario'])) {
    http_response_code(400);
    echo json_encode(array("message" => "Faltan datos de valoración"));
    exit;
}

$sql = "INSERT INTO valoraciones (usuario, calificacion, comentario, destinatario) VALUES (:usuario, :calificacion, :comentario, :destinatario)";

try {
    $stmt = $conn->prepare($sql);
    
    $stmt->bindParam(':usuario', $data['usuario']);
    $stmt->bindParam(':calificacion', $data['calificacion']);
    $stmt->bindParam(':comentario', $data['comentario']);
	$stmt->bindParam(':destinatario', $data['destinatario']);
    
    $stmt->execute();
    
    http_response_code(200);
    echo json_encode(array("message" => "Valoración registrada con éxito"));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Error al registrar la valoración: " . $e->getMessage()));
}
?>