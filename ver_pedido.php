<?php
// ver_pedido.php - Ver detalles de un pedido
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$id_pedido = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_pedido <= 0) {
    header('Location: usuario.php');
    exit;
}
// Cargar pedido
$sql = "SELECT p.*, d.direccion, d.ciudad, d.provincia, d.codigo_postal, d.pais, m.tipo AS metodo_tipo, m.titular, m.numero_tarjeta, m.paypal_email, m.iban FROM pedidos p JOIN direcciones_envio d ON p.id_direccion = d.id_direccion LEFT JOIN metodos_pago m ON p.id_metodo = m.id_metodo WHERE p.id_pedido = ? AND p.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id_pedido, $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$pedido = $res->fetch_assoc();
$stmt->close();
if (!$pedido) {
    header('Location: usuario.php');
    exit;
}
// Cargar productos del pedido
$productos = [];
$sql = "SELECT pd.*, pr.nombre, pr.imagen FROM pedido_detalle pd JOIN productos pr ON pd.id_producto = pr.id_producto WHERE pd.id_pedido = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_pedido);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $productos[] = $row;
$stmt->close();
include 'header.php';
?><meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4 mb-5 pedido-detalle">
    <h2 class="mb-4">Detalle del pedido #<?= $pedido['id_pedido'] ?></h2>
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="bg-white rounded shadow p-4 mb-4">
                <h5>Datos del pedido</h5>
                <div><b>Fecha:</b> <?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></div>
                <div><b>Estado:</b> <?= htmlspecialchars($pedido['estado']) ?></div>
                <div><b>Total:</b> €<?= number_format($pedido['total'],2) ?></div>
            </div>
            <div class="bg-white rounded shadow p-4 mb-4">
                <h5>Dirección de envío</h5>
                <div><?= htmlspecialchars($pedido['direccion']) ?>, <?= htmlspecialchars($pedido['ciudad']) ?>, <?= htmlspecialchars($pedido['provincia']) ?></div>
                <div><?= htmlspecialchars($pedido['codigo_postal']) ?>, <?= htmlspecialchars($pedido['pais']) ?></div>
            </div>
            <div class="bg-white rounded shadow p-4 mb-4">
                <h5>Método de pago</h5>
                <div>
                    <?php if ($pedido['metodo_tipo'] == 'tarjeta'): ?>
                        Tarjeta (<?= htmlspecialchars($pedido['numero_tarjeta']) ?>)<?= $pedido['titular'] ? ' - '.htmlspecialchars($pedido['titular']) : '' ?>
                    <?php elseif ($pedido['metodo_tipo'] == 'paypal'): ?>
                        PayPal (<?= htmlspecialchars($pedido['paypal_email']) ?>)
                    <?php elseif ($pedido['metodo_tipo'] == 'transferencia'): ?>
                        Transferencia (<?= htmlspecialchars($pedido['iban']) ?>)
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="bg-white rounded shadow p-4 mb-4">
                <h5>Productos</h5>
                <div class="list-group">
                    <?php foreach ($productos as $prod): ?>
                    <div class="list-group-item d-flex align-items-center gap-3 mb-2 rounded">
                        <img src="img/<?= htmlspecialchars($prod['imagen']) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>" style="height:60px;max-width:60px;" class="rounded">
                        <div class="flex-grow-1">
                            <div class="fw-bold mb-1"><?= htmlspecialchars($prod['nombre']) ?></div>
                            <div class="small">Cantidad: <?= $prod['cantidad'] ?> &nbsp; | &nbsp; Precio: €<?= number_format($prod['precio_unitario'],2) ?></div>
                        </div>
                        <div class="fw-bold">€<?= number_format($prod['cantidad'] * $prod['precio_unitario'],2) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <a href="usuario.php" class="btn btn-outline-secondary mt-3">Volver a mis pedidos</a>
</div>
<?php include 'footer.php'; ?>
