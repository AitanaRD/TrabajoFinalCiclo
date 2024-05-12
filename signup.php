<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require("db_connect.php");

$nombre = $_REQUEST["nombre"];
$edad = $_REQUEST["edad"];
$correo = $_REQUEST["correo"];
$contrasena = password_hash($_REQUEST["contrasena"], PASSWORD_BCRYPT);
$ubicacion = $_REQUEST["ubicacion"];

$sql = "INSERT INTO usuarios (nombre, edad, correo, contrasena, ubicacion) 
        VALUES (:nombre, :edad, :correo, :contrasena, :ubicacion)";

try {
    $result = $conn->prepare($sql);
    $values = array(":nombre" => $nombre, ":edad" => $edad, ":correo" => $correo, ":contrasena" => $contrasena, ":ubicacion" => $ubicacion);
    $result->execute($values);

    if ($result->rowCount() > 0) {
        echo json_encode(array("message" => "success"));
    } else {
        echo json_encode(array("message" => "error"));
    }
} catch (PDOException $e) {
    die("An error has occurred! " . $e->getMessage());
}
?>