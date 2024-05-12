<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require("db_connect.php");

$categoria = $_GET['categoria'];
$tipo_intercambio = $_GET['tipo_intercambio'];

if ($tipo_intercambio === 'enseñar') {
    // Si quiere aprender, buscar usuarios que quieren enseñar
    $sql = "SELECT DISTINCT u.* FROM usuarios u
            INNER JOIN habilidad_usuario hu ON u.usuario_id = hu.usuario_id
            INNER JOIN habilidades h ON hu.habilidad_id = h.habilidad_id
            WHERE LOWER(h.categoria) LIKE LOWER(:categoria)
            AND h.tipoIntercambio = 'aprender'";
} else {
    // Si quiere enseñar, buscar usuarios que quieren aprender
    $sql = "SELECT DISTINCT u.* FROM usuarios u
            INNER JOIN habilidad_usuario hu ON u.usuario_id = hu.usuario_id
            INNER JOIN habilidades h ON hu.habilidad_id = h.habilidad_id
            WHERE LOWER(h.categoria) LIKE LOWER(:categoria)
            AND h.tipoIntercambio = 'enseñar'";
}

$stmt = $conn->prepare($sql);
$stmt->bindParam(':categoria', $categoria);
$stmt->execute();

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$num_rows = count($usuarios);

if ($num_rows > 0) {
    echo json_encode(array("usuarios" => $usuarios));
} else {
    echo json_encode(array("mensaje" => "No se encontraron usuarios que coincidan con los criterios de búsqueda."));
}

$conn = null;
?>