<?php
header("Access-Control-Allow-Origin: http://localhost:8101");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require("db_connect.php");

$habilidad = '%' . $_GET['habilidad'] . '%';
$tipo_intercambio = $_GET['tipo_intercambio'];

if ($tipo_intercambio === 'enseñar') {
  // Si quiere enseñar, buscar usuarios que quieran aprender
  $sql = "SELECT DISTINCT u.* FROM usuarios u
          INNER JOIN habilidad_usuario hu ON u.usuario_id = hu.usuario_id
          INNER JOIN habilidades h ON hu.habilidad_id = h.habilidad_id
          WHERE LOWER(h.nombreHabilidad) LIKE LOWER(:habilidad)
          AND h.tipoIntercambio = 'aprender'";
} else {
  // Si quiere aprender, buscar usuarios que quieran enseñar
  $sql = "SELECT DISTINCT u.* FROM usuarios u
          INNER JOIN habilidad_usuario hu ON u.usuario_id = hu.usuario_id
          INNER JOIN habilidades h ON hu.habilidad_id = h.habilidad_id
          WHERE LOWER(h.nombreHabilidad) LIKE LOWER(:habilidad)
          AND h.tipoIntercambio = 'enseñar'";
}

$stmt = $conn->prepare($sql);
$stmt->bindParam(':habilidad', $habilidad);
$stmt->execute();

if ($stmt->rowCount() > 0) {
  $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode(array('usuarios' => $usuarios));
} else {
  echo json_encode(array('usuarios' => array()));
}

$conn = null;
?>