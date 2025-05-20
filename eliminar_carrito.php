<?php
// eliminar_carrito.php - Eliminar producto del carrito
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$id_carrito_detalle = intval($_POST['id_carrito_detalle'] ?? 0);
if ($id_carrito_detalle <= 0) {
    header('Location: carrito.php');
    exit;
}
$sql = "DELETE cd FROM carrito_detalle cd JOIN carrito c ON cd.id_carrito = c.id_carrito WHERE cd.id_carrito_detalle = ? AND c.id_usuario = ? AND c.estado = 'activo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id_carrito_detalle, $id_usuario);
$stmt->execute();
$stmt->close();
header('Location: carrito.php');
exit;
