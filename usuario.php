<?php
// usuario.php - Perfil de usuario: datos, pedidos, direcciones y métodos de pago
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
// Cargar datos usuario
$sql = "SELECT nombre, apellidos, email, telefono, fecha_nacimiento FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$stmt->bind_result($nombre, $apellidos, $email, $telefono, $fecha_nacimiento);
$stmt->fetch();
$stmt->close();
// Cargar direcciones
$direcciones = [];
$res = $conn->query("SELECT * FROM direcciones_envio WHERE id_usuario = $id_usuario");
if ($res) while ($row = $res->fetch_assoc()) $direcciones[] = $row;
// Cargar métodos de pago
$metodos = [];
$res = $conn->query("SELECT * FROM metodos_pago WHERE id_usuario = $id_usuario");
if ($res) while ($row = $res->fetch_assoc()) $metodos[] = $row;
// Cargar pedidos
$pedidos = [];
$res = $conn->query("SELECT * FROM pedidos WHERE id_usuario = $id_usuario ORDER BY fecha_pedido DESC");
if ($res) while ($row = $res->fetch_assoc()) $pedidos[] = $row;
include 'header.php';
?><meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4 mb-5">
    <h2 class="mb-4">Mi perfil</h2>
    <div class="row g-4">
        <!-- Datos personales -->
        <div class="col-12 col-lg-6">
            <div class="bg-white rounded shadow p-4 mb-4">
                <h5>Datos personales</h5>
                <form method="post" action="actualizar_usuario.php">
                    <div class="mb-2"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>" required></div>
                    <div class="mb-2"><label class="form-label">Apellidos</label><input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($apellidos) ?>" required></div>
                    <div class="mb-2"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required></div>
                    <div class="mb-2"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($telefono) ?>"></div>
                    <div class="mb-2"><label class="form-label">Fecha nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control" value="<?= $fecha_nacimiento ? date('Y-m-d', strtotime($fecha_nacimiento)) : '' ?>"></div>
                    <button type="submit" class="btn btn-warning mt-2">Guardar cambios</button>
                </form>
                <form method="post" action="cambiar_password.php" class="mt-3">
                    <h6>Cambiar contraseña</h6>
                    <div class="mb-2"><input type="password" name="password_actual" class="form-control" placeholder="Contraseña actual" required></div>
                    <div class="mb-2"><input type="password" name="password_nueva" class="form-control" placeholder="Nueva contraseña" required></div>
                    <button type="submit" class="btn btn-outline-primary">Cambiar contraseña</button>
                </form>
            </div>
        </div>
        <!-- Direcciones y métodos de pago -->
        <div class="col-12 col-lg-6">
            <div class="bg-white rounded shadow p-4 mb-4">
                <h5>Direcciones de envío</h5>
                <?php foreach ($direcciones as $dir): ?>
                    <div class="border rounded p-2 mb-2">
                        <div><?= htmlspecialchars($dir['direccion']) ?>, <?= htmlspecialchars($dir['ciudad']) ?> (<?= htmlspecialchars($dir['alias']) ?>)</div>
                        <div class="small text-muted">CP: <?= htmlspecialchars($dir['codigo_postal']) ?>, <?= htmlspecialchars($dir['provincia']) ?>, <?= htmlspecialchars($dir['pais']) ?></div>
                        <form method="post" action="eliminar_direccion.php" class="d-inline"><input type="hidden" name="id_direccion" value="<?= $dir['id_direccion'] ?>"><button class="btn btn-sm btn-danger">Eliminar</button></form>
                        <a href="editar_direccion.php?id=<?= $dir['id_direccion'] ?>" class="btn btn-sm btn-outline-primary ms-2">Editar</a>
                    </div>
                <?php endforeach; ?>
                <a href="nueva_direccion.php" class="btn btn-sm btn-success mt-2">Añadir dirección</a>
            </div>
            <div class="bg-white rounded shadow p-4 mb-4">
                <h5>Métodos de pago</h5>
                <?php foreach ($metodos as $m): ?>
                    <div class="border rounded p-2 mb-2">
                        <div><?= htmlspecialchars($m['tipo']) ?> <?= $m['tipo']=='tarjeta' ? '('.htmlspecialchars($m['numero_tarjeta']).')' : '' ?> <?= $m['tipo']=='paypal' ? '('.htmlspecialchars($m['paypal_email']).')' : '' ?> <?= $m['tipo']=='transferencia' ? '('.htmlspecialchars($m['iban']).')' : '' ?></div>
                        <form method="post" action="eliminar_metodo.php" class="d-inline"><input type="hidden" name="id_metodo" value="<?= $m['id_metodo'] ?>"><button class="btn btn-sm btn-danger">Eliminar</button></form>
                        <a href="editar_metodo.php?id=<?= $m['id_metodo'] ?>" class="btn btn-sm btn-outline-primary ms-2">Editar</a>
                    </div>
                <?php endforeach; ?>
                <a href="nuevo_metodo.php" class="btn btn-sm btn-success mt-2">Añadir método de pago</a>
            </div>
        </div>
    </div>
    <!-- Pedidos -->
    <div class="bg-white rounded shadow p-4 mt-4">
        <h5>Mis pedidos</h5>
        <?php if (count($pedidos) == 0): ?>
            <div class="alert alert-info">No tienes pedidos realizados.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead><tr><th>ID</th><th>Fecha</th><th>Total</th><th>Estado</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td><?= $p['id_pedido'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($p['fecha_pedido'])) ?></td>
                            <td>€<?= number_format($p['total'],2) ?></td>
                            <td><?= htmlspecialchars($p['estado']) ?></td>
                            <td><a href="ver_pedido.php?id=<?= $p['id_pedido'] ?>" class="btn btn-sm btn-outline-secondary">Ver</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>
