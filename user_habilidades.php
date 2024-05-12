<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$usuario_id = $_COOKIE['usuario_id'];

require("db_connect.php");

$sql = "SELECT habilidades.habilidad_id, habilidades.nombreHabilidad, habilidades.descripcion, habilidades.categoria, habilidades.tipoIntercambio
        FROM habilidades 
        INNER JOIN habilidad_usuario ON habilidades.habilidad_id = habilidad_usuario.habilidad_id 
        WHERE habilidad_usuario.usuario_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($usuario_id));
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(array("habilidades" => $result));

?>