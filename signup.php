<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require("db_connect.php");

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['nombre']) && isset($data['edad']) && isset($data['correo']) && isset($data['contrasena']) && isset($data['ubicacion'])) {
    $nombre = $data['nombre'];
    $edad = $data['edad'];
    $correo = $data['correo'];
    $contrasena = password_hash($data['contrasena'], PASSWORD_BCRYPT);
    $ubicacion = $data['ubicacion'];

    $sql_check = "SELECT * FROM usuarios WHERE correo = :correo";
    try {
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->execute(array(":correo" => $correo));
        if ($stmt_check->rowCount() > 0) {
            echo json_encode(array("message" => "email_exists"));
            exit();
        }
        
        $sql = "INSERT INTO usuarios (nombre, edad, correo, contrasena, ubicacion) 
                VALUES (:nombre, :edad, :correo, :contrasena, :ubicacion)";
        $stmt = $conn->prepare($sql);
        $values = array(":nombre" => $nombre, ":edad" => $edad, ":correo" => $correo, ":contrasena" => $contrasena, ":ubicacion" => $ubicacion);
        $stmt->execute($values);

        if ($stmt->rowCount() > 0) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "error"));
        }
    } catch (PDOException $e) {
        echo json_encode(array("message" => "error", "error" => $e->getMessage()));
    }
} else {
    echo json_encode(array("message" => "error", "error" => "Faltan datos en la solicitud."));
}
?>