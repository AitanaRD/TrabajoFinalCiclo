<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->usuarioAceptado, $data->mensaje, $data->correoRemitente)) {
        try {
            require("db_connect.php");

            $sql = "INSERT INTO notificaciones (usuario_aceptado, mensaje, correo_remitente) VALUES (:usuarioAceptado, :mensaje, :correoRemitente)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':usuarioAceptado', $data->usuarioAceptado);
            $stmt->bindParam(':mensaje', $data->mensaje);
            $stmt->bindParam(':correoRemitente', $data->correoRemitente);

            if ($stmt->execute()) {
                echo json_encode(array("status" => "success"));
            } else {
                echo json_encode(array("status" => "error", "message" => "No se pudo guardar la notificación en la base de datos"));
            }

            $stmt = null;
            $conn = null;
        } catch (PDOException $e) {
            echo json_encode(array("status" => "error", "message" => "Error de base de datos: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Datos insuficientes"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Método de solicitud no válido"));
}
?>