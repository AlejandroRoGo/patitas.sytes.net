<?php
// actualizar_usuario.php - Actualiza datos personales del usuario
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
if ($nombre && $apellidos && $email) {
    // Comprobar si el email ya existe para otro usuario
    $sql = "SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $email, $id_usuario);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        header('Location: usuario.php?error=email');
        exit;
    }
    $stmt->close();
    // Actualizar datos
    $sql = "UPDATE usuarios SET nombre=?, apellidos=?, email=?, telefono=?, fecha_nacimiento=? WHERE id_usuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $nombre, $apellidos, $email, $telefono, $fecha_nacimiento, $id_usuario);
    $stmt->execute();
    $stmt->close();
}
header('Location: usuario.php');
exit;
