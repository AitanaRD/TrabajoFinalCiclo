<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require("db_connect.php");

$nombre = $_REQUEST["nombre"];
$contrasena = $_REQUEST["contrasena"];

$sql = "SELECT * FROM usuarios WHERE nombre = :nombre";

try {
    $result = $conn->prepare($sql);
    $values = array(":nombre" => $nombre);
    $result->execute($values);

    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);

        if (password_verify($contrasena, $row['contrasena'])) {
            $sesion_id = uniqid();
            setcookie("sesion_id", $sesion_id, time() + (86400 * 30), "/"); // Caduca en 30 días
            setcookie("usuario_id", $row['usuario_id'], time() + (86400 * 30), "/");

            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "error", "reason" => "Contraseña incorrecta"));
        }
    } else {
        echo json_encode(array("message" => "error", "reason" => "Usuario no encontrado"));
    }
} catch (PDOException $e) {
    die("An error has occurred! " . $e->getMessage());
}
?>