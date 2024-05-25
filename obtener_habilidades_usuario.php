<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

try {
    if (isset($_COOKIE['usuario_id']) && !empty($_COOKIE['usuario_id'])) {
        $usuario_id = $_COOKIE['usuario_id'];
        require("db_connect.php");

        $sql = "SELECT habilidades.habilidad_id, habilidades.nombreHabilidad
                FROM habilidades 
                INNER JOIN habilidad_usuario ON habilidades.habilidad_id = habilidad_usuario.habilidad_id 
                WHERE habilidad_usuario.usuario_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute(array($usuario_id));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;

        // Enviar las habilidades como respuesta JSON
        echo json_encode(array("habilidades" => $result));
    } else {
        echo json_encode(array("error" => "Usuario no válido"));
    }
} catch (Exception $e) {
    // Manejo de errores
    echo json_encode(array("error" => "Error en el servidor: " . $e->getMessage()));
}
?>