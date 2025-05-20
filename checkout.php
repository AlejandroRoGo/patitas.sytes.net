<?php
// checkout.php - Selección de dirección y método de pago para finalizar pedido
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
if (!$id_carrito) {
    header('Location: carrito.php');
    exit;
}
// Cargar direcciones y métodos de pago
$direcciones = [];
$res = $conn->query("SELECT * FROM direcciones_envio WHERE id_usuario = $id_usuario");
if ($res) while ($row = $res->fetch_assoc()) $direcciones[] = $row;
$metodos = [];
$res = $conn->query("SELECT * FROM metodos_pago WHERE id_usuario = $id_usuario");
if ($res) while ($row = $res->fetch_assoc()) $metodos[] = $row;
// Procesar pedido
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_direccion = intval($_POST['id_direccion'] ?? 0);
    $id_metodo = intval($_POST['id_metodo'] ?? 0);
    if ($id_direccion && $id_metodo) {
        // Calcular total
        $total = 0;
        $sql = "SELECT cantidad, precio_unitario FROM carrito_detalle WHERE id_carrito = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_carrito);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $total += $row['cantidad'] * $row['precio_unitario'];
        $stmt->close();
        // Insertar pedido
        $sql = "INSERT INTO pedidos (id_usuario, id_direccion, id_metodo, total, estado) VALUES (?, ?, ?, ?, 'pendiente')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiid', $id_usuario, $id_direccion, $id_metodo, $total);
        if ($stmt->execute()) {
            $id_pedido = $stmt->insert_id;
            // Pasar productos del carrito a pedido_detalle
            $sql = "INSERT INTO pedido_detalle (id_pedido, id_producto, cantidad, precio_unitario) SELECT ?, id_producto, cantidad, precio_unitario FROM carrito_detalle WHERE id_carrito = ?";
            $stmt2 = $conn->prepare($sql);
            $stmt2->bind_param('ii', $id_pedido, $id_carrito);
            $stmt2->execute();
            $stmt2->close();
            // RESTAR STOCK de cada producto
            $sql = "SELECT id_producto, cantidad FROM carrito_detalle WHERE id_carrito = ?";
            $stmt3 = $conn->prepare($sql);
            $stmt3->bind_param('i', $id_carrito);
            $stmt3->execute();
            $res = $stmt3->get_result();
            while ($row = $res->fetch_assoc()) {
                $sql_update = "UPDATE productos SET stock = stock - ? WHERE id_producto = ? AND stock >= ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param('iii', $row['cantidad'], $row['id_producto'], $row['cantidad']);
                $stmt_update->execute();
                $stmt_update->close();
            }
            $stmt3->close();
            // Vaciar carrito
            $conn->query("DELETE FROM carrito_detalle WHERE id_carrito = $id_carrito");
            $conn->query("UPDATE carrito SET estado = 'convertido' WHERE id_carrito = $id_carrito");
            // Mensaje de éxito y redirección
            $_SESSION['pedido_exito'] = true;
            header('Location: usuario.php');
            exit;
        } else {
            $error = 'Error al procesar el pedido.';
        }
        $stmt->close();
    } else {
        $error = 'Selecciona dirección y método de pago.';
    }
}
include 'header.php';
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-5 mb-5" style="max-width:600px;">
    <div class="bg-white rounded shadow p-4">
        <h2 class="mb-4 text-center">Finalizar compra</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="id_direccion" class="form-label">Dirección de envío</label>
                <select name="id_direccion" id="id_direccion" class="form-select" required>
                    <option value="">Selecciona...</option>
                    <?php foreach ($direcciones as $dir): ?>
                        <option value="<?= $dir['id_direccion'] ?>"><?= htmlspecialchars($dir['direccion']) ?> (<?= htmlspecialchars($dir['alias']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <a href="nueva_direccion.php" class="btn btn-link btn-sm">Añadir nueva dirección</a>
            </div>
            <div class="mb-3">
                <label for="id_metodo" class="form-label">Método de pago</label>
                <select name="id_metodo" id="id_metodo" class="form-select" required>
                    <option value="">Selecciona...</option>
                    <?php foreach ($metodos as $m): ?>
                        <option value="<?= $m['id_metodo'] ?>">
                            <?= htmlspecialchars($m['tipo']) ?>
                            <?= $m['tipo']=='tarjeta' ? '('.htmlspecialchars($m['numero_tarjeta']).')' : '' ?>
                            <?= $m['tipo']=='paypal' ? '('.htmlspecialchars($m['paypal_email']).')' : '' ?>
                            <?= $m['tipo']=='transferencia' ? '('.htmlspecialchars($m['iban']).')' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <a href="nuevo_metodo.php" class="btn btn-link btn-sm">Añadir nuevo método</a>
            </div>
            <button type="submit" class="btn btn-success w-100">Realizar pedido</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
