<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require("db_connect.php");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->usuario)) {
            $correo_usuario = $data->usuario->correo;

            $asunto = "Solicitud de intercambio recibida";
            $mensaje = "Hola,\n\nHas recibido una solicitud de intercambio de habilidades. Por favor, revisa tu cuenta para más detalles.";
            mail($correo_usuario, $asunto, $mensaje);

            $stmt = $conn->prepare("INSERT INTO solicitudes (nombre_usuario, edad_usuario, ubicacion_usuario, correo_solicitante, fecha_solicitud) 
                                    VALUES (:nombre, :edad, :ubicacion, :correo_solicitante, NOW())");

            $stmt->bindParam(':nombre', $data->usuario->nombre);
            $stmt->bindParam(':edad', $data->usuario->edad);
            $stmt->bindParam(':ubicacion', $data->usuario->ubicacion);
            $stmt->bindParam(':correo_solicitante', $data->solicitante->correo);

            $stmt->execute();

            http_response_code(200);
            echo json_encode(array("message" => "Solicitud enviada correctamente a $correo_usuario y guardada en la base de datos."));
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos. No se proporcionó el usuario."));
        }
    } else {
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido. Se esperaba una solicitud POST."));
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Error al conectar a la base de datos: " . $e->getMessage()));
}

$conn = null;
?>