<?php
// cambiar_password.php - Cambia la contraseña del usuario
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$actual = $_POST['password_actual'] ?? '';
$nueva = $_POST['password_nueva'] ?? '';
if ($actual && $nueva) {
    $sql = "SELECT password FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $stmt->bind_result($hash);
    if ($stmt->fetch() && password_verify($actual, $hash)) {
        $stmt->close();
        $nuevo_hash = password_hash($nueva, PASSWORD_DEFAULT);
        $sql2 = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param('si', $nuevo_hash, $id_usuario);
        $stmt2->execute();
        $stmt2->close();
        header('Location: usuario.php?msg=ok');
        exit;
    } else {
        $stmt->close();
        header('Location: usuario.php?error=pass');
        exit;
    }
}
header('Location: usuario.php');
exit;
