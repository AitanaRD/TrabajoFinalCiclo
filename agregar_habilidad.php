<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = file_get_contents("php://input");

    $request_data = json_decode($data, true);

    if(isset($_COOKIE['usuario_id'], $request_data['nombreHabilidad'], $request_data['descripcion'], $request_data['categoria'], $request_data['tipoIntercambio'])) {
        $user_id = $_COOKIE['usuario_id'];
        $nombreHabilidad = htmlspecialchars($request_data['nombreHabilidad']);
        $descripcion = htmlspecialchars($request_data['descripcion']);
        $categoria = htmlspecialchars($request_data['categoria']);
        $tipoIntercambio = htmlspecialchars($request_data['tipoIntercambio']);

        require("db_connect.php");

        $stmt = $conn->prepare("INSERT INTO habilidades (nombreHabilidad, descripcion, categoria, tipoIntercambio) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombreHabilidad, $descripcion, $categoria, $tipoIntercambio]);

        if ($stmt->rowCount() > 0) {
            $habilidad_id = $conn->lastInsertId();

            $stmt_relacion = $conn->prepare("INSERT INTO habilidad_usuario (usuario_id, habilidad_id) VALUES (?, ?)");
            $stmt_relacion->execute([$user_id, $habilidad_id]);

            if ($stmt_relacion->rowCount() > 0) {
                echo json_encode(array("success" => true));
            } else {
                echo json_encode(array("success" => false, "message" => "Error al agregar la habilidad al usuario"));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Error al agregar la nueva habilidad"));
        }

        $conn = null;
    } else {
        echo json_encode(array("success" => false, "message" => "Datos insuficientes para agregar la habilidad"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "La solicitud debe ser de tipo POST"));
}
?>