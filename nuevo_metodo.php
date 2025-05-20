<?php
// nuevo_metodo.php - Añadir nuevo método de pago
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $titular = trim($_POST['titular'] ?? '');
    $numero_tarjeta = trim($_POST['numero_tarjeta'] ?? '');
    $caducidad = trim($_POST['caducidad'] ?? '');
    $paypal_email = trim($_POST['paypal_email'] ?? '');
    $iban = trim($_POST['iban'] ?? '');
    $token = uniqid('tok_', true);
    if ($tipo == 'tarjeta' && $titular && $numero_tarjeta && $caducidad) {
        $sql = "INSERT INTO metodos_pago (id_usuario, tipo, titular, numero_tarjeta, caducidad, token) VALUES (?, 'tarjeta', ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issss', $id_usuario, $titular, $numero_tarjeta, $caducidad, $token);
        $stmt->execute();
        $stmt->close();
        header('Location: usuario.php');
        exit;
    } elseif ($tipo == 'paypal' && $paypal_email) {
        $sql = "INSERT INTO metodos_pago (id_usuario, tipo, paypal_email, token) VALUES (?, 'paypal', ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $id_usuario, $paypal_email, $token);
        $stmt->execute();
        $stmt->close();
        header('Location: usuario.php');
        exit;
    } elseif ($tipo == 'transferencia' && $iban) {
        $sql = "INSERT INTO metodos_pago (id_usuario, tipo, iban, token) VALUES (?, 'transferencia', ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $id_usuario, $iban, $token);
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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-5 mb-5" style="max-width:500px;">
    <div class="bg-white rounded shadow p-4">
        <h2 class="mb-4 text-center">Nuevo método de pago</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" novalidate>
            <div class="mb-3">
                <label class="form-label">Tipo*</label>
                <select name="tipo" class="form-select" required onchange="this.form.submit()">
                    <option value="">Selecciona...</option>
                    <option value="tarjeta" <?= (isset($_POST['tipo']) && $_POST['tipo']=='tarjeta')?'selected':'' ?>>Tarjeta</option>
                    <option value="paypal" <?= (isset($_POST['tipo']) && $_POST['tipo']=='paypal')?'selected':'' ?>>PayPal</option>
                    <option value="transferencia" <?= (isset($_POST['tipo']) && $_POST['tipo']=='transferencia')?'selected':'' ?>>Transferencia</option>
                </select>
            </div>
            <?php if ($_POST['tipo'] ?? '' == 'tarjeta'): ?>
                <div class="mb-3"><label class="form-label">Titular*</label><input type="text" name="titular" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Número de tarjeta*</label><input type="text" name="numero_tarjeta" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Caducidad*</label><input type="text" name="caducidad" class="form-control" placeholder="MM/AAAA" required></div>
            <?php elseif ($_POST['tipo'] ?? '' == 'paypal'): ?>
                <div class="mb-3"><label class="form-label">Email PayPal*</label><input type="email" name="paypal_email" class="form-control" required></div>
            <?php elseif ($_POST['tipo'] ?? '' == 'transferencia'): ?>
                <div class="mb-3"><label class="form-label">IBAN*</label><input type="text" name="iban" class="form-control" required></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-success w-100">Guardar</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
