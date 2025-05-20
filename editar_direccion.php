<?php
// editar_direccion.php - Editar dirección de envío
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$id_direccion = intval($_GET['id'] ?? 0);
if ($id_direccion <= 0) {
    header('Location: usuario.php');
    exit;
}
// Cargar datos actuales
$sql = "SELECT * FROM direcciones_envio WHERE id_direccion = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id_direccion, $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$dir = $res->fetch_assoc();
$stmt->close();
if (!$dir) {
    header('Location: usuario.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = trim($_POST['direccion'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $provincia = trim($_POST['provincia'] ?? '');
    $codigo_postal = trim($_POST['codigo_postal'] ?? '');
    $pais = trim($_POST['pais'] ?? '');
    $telefono_envio = trim($_POST['telefono_envio'] ?? '');
    $alias = trim($_POST['alias'] ?? '');
    if ($direccion && $ciudad && $codigo_postal && $pais) {
        $sql = "UPDATE direcciones_envio SET direccion=?, ciudad=?, provincia=?, codigo_postal=?, pais=?, telefono_envio=?, alias=? WHERE id_direccion=? AND id_usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssii', $direccion, $ciudad, $provincia, $codigo_postal, $pais, $telefono_envio, $alias, $id_direccion, $id_usuario);
        $stmt->execute();
        $stmt->close();
        header('Location: usuario.php');
        exit;
    } else {
        $error = 'Rellena los campos obligatorios.';
    }
}
include 'header.php';
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-5 mb-5" style="max-width:500px;">
    <div class="bg-white rounded shadow p-4">
        <h2 class="mb-4 text-center">Editar dirección</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" novalidate>
            <div class="mb-3"><label class="form-label">Dirección*</label><input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($dir['direccion']) ?>" required></div>
            <div class="mb-3"><label class="form-label">Ciudad*</label><input type="text" name="ciudad" class="form-control" value="<?= htmlspecialchars($dir['ciudad']) ?>" required></div>
            <div class="mb-3"><label class="form-label">Provincia</label><input type="text" name="provincia" class="form-control" value="<?= htmlspecialchars($dir['provincia']) ?>"></div>
            <div class="mb-3"><label class="form-label">Código postal*</label><input type="text" name="codigo_postal" class="form-control" value="<?= htmlspecialchars($dir['codigo_postal']) ?>" required></div>
            <div class="mb-3"><label class="form-label">País*</label><input type="text" name="pais" class="form-control" value="<?= htmlspecialchars($dir['pais']) ?>" required></div>
            <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="telefono_envio" class="form-control" value="<?= htmlspecialchars($dir['telefono_envio']) ?>"></div>
            <div class="mb-3"><label class="form-label">Alias</label><input type="text" name="alias" class="form-control" value="<?= htmlspecialchars($dir['alias']) ?>"></div>
            <button type="submit" class="btn btn-success w-100">Guardar cambios</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
