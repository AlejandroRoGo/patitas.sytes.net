<?php
// agregar_carrito.php - Añadir producto al carrito
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$id_producto = intval($_POST['id_producto'] ?? 0);
$cantidad = intval($_POST['cantidad'] ?? 1);
if ($id_producto <= 0 || $cantidad <= 0) {
    header('Location: tienda.php');
    exit;
}
// Buscar carrito activo
$sql = "SELECT id_carrito FROM carrito WHERE id_usuario = ? AND estado = 'activo' LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$stmt->bind_result($id_carrito);
if (!$stmt->fetch()) {
    $stmt->close();
    // Crear carrito
    $sql2 = "INSERT INTO carrito (id_usuario) VALUES (?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('i', $id_usuario);
    $stmt2->execute();
    $id_carrito = $stmt2->insert_id;
    $stmt2->close();
} else {
    $stmt->close();
}
// Comprobar stock
$sql = "SELECT stock, precio FROM productos WHERE id_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_producto);
$stmt->execute();
$stmt->bind_result($stock, $precio);
if (!$stmt->fetch() || $stock < $cantidad) {
    $stmt->close();
    header('Location: tienda.php?error=stock');
    exit;
}
$stmt->close();
// ¿Ya está en el carrito?
$sql = "SELECT id_carrito_detalle, cantidad FROM carrito_detalle WHERE id_carrito = ? AND id_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id_carrito, $id_producto);
$stmt->execute();
$stmt->bind_result($id_detalle, $cant_actual);
if ($stmt->fetch()) {
    $stmt->close();
    $nueva_cant = $cant_actual + $cantidad;
    if ($nueva_cant > $stock) $nueva_cant = $stock;
    $sql2 = "UPDATE carrito_detalle SET cantidad = ? WHERE id_carrito_detalle = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('ii', $nueva_cant, $id_detalle);
    $stmt2->execute();
    $stmt2->close();
} else {
    $stmt->close();
    $sql2 = "INSERT INTO carrito_detalle (id_carrito, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('iiid', $id_carrito, $id_producto, $cantidad, $precio);
    $stmt2->execute();
    $stmt2->close();
}
header('Location: carrito.php');
exit;
