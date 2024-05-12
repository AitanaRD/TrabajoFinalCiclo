<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require("db_connect.php");

if (isset($_COOKIE['sesion_id']) && isset($_COOKIE['usuario_id'])) {
    $sesion_id = $_COOKIE['sesion_id']; 
    $usuario_id = $_COOKIE['usuario_id']; 

    $sql = "SELECT nombre, edad, correo, ubicacion FROM usuarios WHERE usuario_id = :usuario_id";
    $stmt = $conn->prepare($sql); 
    $stmt->bindParam(':usuario_id', $usuario_id); 
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC); 

    if ($userData) { 
    echo json_encode(array("userData" => $userData));
} else {
    echo json_encode(array("error" => "No se encontraron datos de usuario"));
}
} else {
    echo json_encode(array("error" => "Debes iniciar sesión para acceder a esta página."));
}
?>