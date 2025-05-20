<?php
// editar_metodo.php - Editar método de pago
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$id_metodo = intval($_GET['id'] ?? 0);
if ($id_metodo <= 0) {
    header('Location: usuario.php');
    exit;
}
// Cargar datos actuales
$sql = "SELECT * FROM metodos_pago WHERE id_metodo = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id_metodo, $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$m = $res->fetch_assoc();
$stmt->close();
if (!$m) {
    header('Location: usuario.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $titular = trim($_POST['titular'] ?? '');
    $numero_tarjeta = trim($_POST['numero_tarjeta'] ?? '');
    $caducidad = trim($_POST['caducidad'] ?? '');
    $paypal_email = trim($_POST['paypal_email'] ?? '');
    $iban = trim($_POST['iban'] ?? '');
    if ($tipo == 'tarjeta' && $titular && $numero_tarjeta && $caducidad) {
        $sql = "UPDATE metodos_pago SET tipo='tarjeta', titular=?, numero_tarjeta=?, caducidad=?, paypal_email=NULL, iban=NULL WHERE id_metodo=? AND id_usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssii', $titular, $numero_tarjeta, $caducidad, $id_metodo, $id_usuario);
        $stmt->execute();
        $stmt->close();
        header('Location: usuario.php');
        exit;
    } elseif ($tipo == 'paypal' && $paypal_email) {
        $sql = "UPDATE metodos_pago SET tipo='paypal', titular=NULL, numero_tarjeta=NULL, caducidad=NULL, paypal_email=?, iban=NULL WHERE id_metodo=? AND id_usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sii', $paypal_email, $id_metodo, $id_usuario);
        $stmt->execute();
        $stmt->close();
        header('Location: usuario.php');
        exit;
    } elseif ($tipo == 'transferencia' && $iban) {
        $sql = "UPDATE metodos_pago SET tipo='transferencia', titular=NULL, numero_tarjeta=NULL, caducidad=NULL, paypal_email=NULL, iban=? WHERE id_metodo=? AND id_usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sii', $iban, $id_metodo, $id_usuario);
        $stmt->execute();
        $stmt->close();
        header('Location: usuario.php');
        exit;
    } else {
        $error = 'Rellena los campos obligatorios según el tipo de método.';
    }
}
include 'header.php';
?>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-5 mb-5" style="max-width:500px;">
    <div class="bg-white rounded shadow p-4">
        <h2 class="mb-4 text-center">Editar método de pago</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" novalidate>
            <input type="hidden" name="tipo" value="<?= htmlspecialchars($m['tipo']) ?>">
            <?php if ($m['tipo'] == 'tarjeta'): ?>
                <div class="mb-3"><label class="form-label">Titular*</label><input type="text" name="titular" class="form-control" value="<?= htmlspecialchars($m['titular']) ?>" required></div>
                <div class="mb-3"><label class="form-label">Número de tarjeta*</label><input type="text" name="numero_tarjeta" class="form-control" value="<?= htmlspecialchars($m['numero_tarjeta']) ?>" required></div>
                <div class="mb-3"><label class="form-label">Caducidad*</label><input type="text" name="caducidad" class="form-control" value="<?= htmlspecialchars($m['caducidad']) ?>" required></div>
            <?php elseif ($m['tipo'] == 'paypal'): ?>
                <div class="mb-3"><label class="form-label">Email PayPal*</label><input type="email" name="paypal_email" class="form-control" value="<?= htmlspecialchars($m['paypal_email']) ?>" required></div>
            <?php elseif ($m['tipo'] == 'transferencia'): ?>
                <div class="mb-3"><label class="form-label">IBAN*</label><input type="text" name="iban" class="form-control" value="<?= htmlspecialchars($m['iban']) ?>" required></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-success w-100">Guardar cambios</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
