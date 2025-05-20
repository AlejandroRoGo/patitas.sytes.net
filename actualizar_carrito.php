<?php
// actualizar_carrito.php - Cambiar cantidad de un producto en el carrito
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$id_carrito_detalle = intval($_POST['id_carrito_detalle'] ?? 0);
$cantidad = intval($_POST['cantidad'] ?? 1);
if ($id_carrito_detalle <= 0 || $cantidad <= 0) {
    header('Location: carrito.php');
    exit;
}
// Comprobar stock
$sql = "SELECT cd.id_carrito, cd.id_producto, p.stock FROM carrito_detalle cd JOIN productos p ON cd.id_producto = p.id_producto WHERE cd.id_carrito_detalle = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_carrito_detalle);
$stmt->execute();
$stmt->bind_result($id_carrito, $id_producto, $stock);
if (!$stmt->fetch()) {
    $stmt->close();
    header('Location: carrito.php');
    exit;
}
$stmt->close();
if ($cantidad > $stock) $cantidad = $stock;
// Actualizar cantidad
$sql = "UPDATE carrito_detalle SET cantidad = ? WHERE id_carrito_detalle = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $cantidad, $id_carrito_detalle);
$stmt->execute();
$stmt->close();
header('Location: carrito.php');
exit;
