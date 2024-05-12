<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["correoDestino"]) && isset($_POST["detalles"]) && isset($_POST["fecha"])) {
        try {
            $correoDestino = $_POST["correoDestino"];
            $detalles = $_POST["detalles"];
            $fecha = $_POST["fecha"];

            $stmt = $conn->prepare("INSERT INTO solicitudes_intercambio (correo_destino, detalles, fecha) VALUES (:correoDestino, :detalles, :fecha)");

            $stmt->bindParam(':correoDestino', $correoDestino);
            $stmt->bindParam(':detalles', $detalles);
            $stmt->bindParam(':fecha', $fecha);

            $stmt->execute();

            echo json_encode(array("message" => "Solicitud de intercambio enviada con éxito"));
        } catch(PDOException $e) {
            echo json_encode(array("error" => "Error al enviar la solicitud: " . $e->getMessage()));
        }

        $conn = null;
    } else {
        echo json_encode(array("error" => "Error: datos incompletos en la solicitud"));
    }
} else {

    echo json_encode(array("error" => "Error: esta página solo acepta solicitudes POST"));
}