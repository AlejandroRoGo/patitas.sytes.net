<?php
// carrito.php - Página de carrito de usuario
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
// Buscar carrito activo
$sql = "SELECT id_carrito FROM carrito WHERE id_usuario = ? AND estado = 'activo' LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$stmt->bind_result($id_carrito);
if (!$stmt->fetch()) {
    $id_carrito = null;
}
$stmt->close();
$productos = [];
$total = 0;
if ($id_carrito) {
    $sql = "SELECT cd.id_carrito_detalle, p.id_producto, p.nombre, p.imagen, cd.cantidad, cd.precio_unitario, p.stock FROM carrito_detalle cd JOIN productos p ON cd.id_producto = p.id_producto WHERE cd.id_carrito = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_carrito);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $productos[] = $row;
        $total += $row['cantidad'] * $row['precio_unitario'];
    }
    $stmt->close();
}
include 'header.php';
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4 mb-5">
    <h2 class="mb-4">Mi carrito</h2>
    <?php if (!$id_carrito || count($productos) == 0): ?>
        <div class="alert alert-info">Tu carrito está vacío.</div>
        <a href="tienda.php" class="btn btn-warning">Ir a la tienda</a>
    <?php else: ?>
        <div class="table-responsive mb-4">
            <table class="table align-middle bg-white">
                <thead>
                    <tr>
                        <th></th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($productos as $prod): ?>
                    <tr>
                        <td><img src="img/<?= htmlspecialchars($prod['imagen']) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>" style="height:60px;"></td>
                        <td><a href="producto.php?id=<?= $prod['id_producto'] ?>" class="text-decoration-none text-dark fw-bold"><?= htmlspecialchars($prod['nombre']) ?></a></td>
                        <td>€<?= number_format($prod['precio_unitario'],2) ?></td>
                        <td>
                            <form method="post" action="actualizar_carrito.php" class="d-inline">
                                <input type="hidden" name="id_carrito_detalle" value="<?= $prod['id_carrito_detalle'] ?>">
                                <input type="number" name="cantidad" value="<?= $prod['cantidad'] ?>" min="1" max="<?= $prod['stock'] ?>" class="form-control form-control-sm d-inline-block" style="width:70px;" onchange="this.form.submit()">
                                <button type="submit" class="btn btn-sm btn-outline-primary ms-1 d-none">Actualizar</button>
                            </form>
                        </td>
                        <td>€<?= number_format($prod['cantidad'] * $prod['precio_unitario'],2) ?></td>
                        <td>
                            <form method="post" action="eliminar_carrito.php" onsubmit="return confirm('¿Eliminar este producto del carrito?');">
                                <input type="hidden" name="id_carrito_detalle" value="<?= $prod['id_carrito_detalle'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Quitar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="tienda.php" class="btn btn-outline-secondary">Seguir comprando</a>
            <div class="fs-5 fw-bold">Total: €<?= number_format($total,2) ?></div>
            <a href="checkout.php" class="btn btn-success">Terminar compra</a>
        </div>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
