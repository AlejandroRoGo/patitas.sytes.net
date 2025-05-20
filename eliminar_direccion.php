<?php
// eliminar_direccion.php - Eliminar dirección de envío
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$id_direccion = intval($_POST['id_direccion'] ?? 0);
if ($id_direccion > 0) {
    $sql = "DELETE FROM direcciones_envio WHERE id_direccion = ? AND id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_direccion, $id_usuario);
    $stmt->execute();
    $stmt->close();
}
header('Location: usuario.php');
exit;
