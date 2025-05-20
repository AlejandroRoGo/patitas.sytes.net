<?php
// eliminar_metodo.php - Eliminar método de pago
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$id_metodo = intval($_POST['id_metodo'] ?? 0);
if ($id_metodo > 0) {
    $sql = "DELETE FROM metodos_pago WHERE id_metodo = ? AND id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_metodo, $id_usuario);
    $stmt->execute();
    $stmt->close();
}
header('Location: usuario.php');
exit;
