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

        $sql = "SELECT correo
                FROM usuarios 
                WHERE usuario_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute(array($usuario_id));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;

        if ($result && isset($result[0]['correo'])) {
            echo json_encode(array("correo" => $result[0]['correo']));
        } else {
            echo json_encode(array("error" => "No se encontró el correo del usuario"));
        }
    } else {
        echo json_encode(array("error" => "Usuario no válido"));
    }
} catch (Exception $e) {
    echo json_encode(array("error" => "Error en el servidor: " . $e->getMessage()));
}
?>
