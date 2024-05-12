<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["detalles"]) && isset($_POST["fecha"]) && isset($_POST["correoDestino"])) {
        try {
            $detalles = $_POST["detalles"];
            $fecha = $_POST["fecha"];
            $correoDestino = $_POST["correoDestino"];

            $stmt = $conn->prepare("INSERT INTO solicitudes_intercambio (detalles, fecha, correo_destino) VALUES (:detalles, :fecha, :correoDestino)");

            $stmt->bindParam(':detalles', $detalles);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':correoDestino', $correoDestino);

            $stmt->execute();

            echo json_encode(array("message" => "Solicitud de intercambio enviada con éxito"));
        } catch(PDOException $e) {
            echo json_encode(array("error" => "Error al enviar la solicitud: " . $e->getMessage()));
        }

        $conn = null;
    } else {
        echo json_encode(array("error" => "Error: Datos incompletos en la solicitud"));
    }
} else {
    echo json_encode(array("error" => "Error: Esta página solo acepta solicitudes POST"));
}
?>