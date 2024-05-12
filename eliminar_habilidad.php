<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $habilidadAEliminar = json_decode(file_get_contents("php://input"));

    require("db_connect.php");

    try {
        $sqlDeleteUsuario = "DELETE FROM habilidad_usuario WHERE habilidad_id = :habilidad_id";
        $stmtDeleteUsuario = $conn->prepare($sqlDeleteUsuario);
        $stmtDeleteUsuario->bindParam(':habilidad_id', $habilidadAEliminar->habilidad_id);
        $stmtDeleteUsuario->execute();

        $sqlDeleteHabilidad = "DELETE FROM habilidades WHERE habilidad_id = :habilidad_id";
        $stmtDeleteHabilidad = $conn->prepare($sqlDeleteHabilidad);
        $stmtDeleteHabilidad->bindParam(':habilidad_id', $habilidadAEliminar->habilidad_id);
        $stmtDeleteHabilidad->execute();

        echo json_encode(array("success" => true));
    } catch(PDOException $e) {
        echo json_encode(array("success" => false, "message" => "Error al eliminar la habilidad: " . $e->getMessage()));
    }

    $conn = null;
} else {
    echo json_encode(array("success" => false, "message" => "Método no permitido"));
}
?>