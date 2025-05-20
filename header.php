<?php
// header.php - Cabecera común para todas las páginas
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'db_config.php';
// Cargar categorías para el menú
$categorias_menu = [];
$sql_cat_menu = "SELECT id_categoria, nombre FROM categorias ORDER BY nombre";
$res_cat_menu = $conn->query($sql_cat_menu);
if ($res_cat_menu) {
    while ($row = $res_cat_menu->fetch_assoc()) {
        $categorias_menu[] = $row;
    }
}
// Contador de productos en carrito (si está logueado)
$carrito_count = 0;
if (isset($_SESSION['id_usuario'])) {
    $sql_cart = "SELECT c.id_carrito FROM carrito c WHERE c.id_usuario = ? AND c.estado = 'activo' LIMIT 1";
    $stmt = $conn->prepare($sql_cart);
    $stmt->bind_param('i', $_SESSION['id_usuario']);
    $stmt->execute();
    $stmt->bind_result($id_carrito);
    if ($stmt->fetch()) {
        $stmt->close();
        $sql_count = "SELECT SUM(cantidad) FROM carrito_detalle WHERE id_carrito = ?";
        $stmt2 = $conn->prepare($sql_count);
        $stmt2->bind_param('i', $id_carrito);
        $stmt2->execute();
        $stmt2->bind_result($carrito_count);
        $stmt2->fetch();
        $stmt2->close();
        if (!$carrito_count) $carrito_count = 0;
    } else {
        $stmt->close();
    }
}
?>
<header class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="img/logo.png" alt="Patitas" style="height:48px;width:auto;max-width:120px;" class="me-2">
            <span class="logo d-none d-md-inline">Patitas</span>
        </a>
        <!-- Categorías en el centro -->
        <nav class="mx-auto d-none d-lg-block">
            <?php foreach ($categorias_menu as $cat): ?>
                <a href="tienda.php?categoria=<?= $cat['id_categoria'] ?>" class="mx-2 nav-link d-inline-block p-0 text-white fw-bold">
                    <?= htmlspecialchars($cat['nombre']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <!-- Botones a la derecha -->
        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <a href="carrito.php" class="cart-icon position-relative me-3">
                    <i class="bi bi-cart-fill"></i>
                    <?php if ($carrito_count > 0): ?>
                        <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $carrito_count ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="usuario.php" class="btn btn-outline-light me-2">Mi cuenta</a>
                <a href="logout.php" class="btn btn-warning">Salir</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light me-2">Login</a>
                <a href="register.php" class="btn btn-warning">Register</a>
            <?php endif; ?>
        </div>
    </div>
    <!-- Categorías en móvil -->
    <div class="container-fluid d-lg-none mt-2">
        <nav class="text-center">
            <?php foreach ($categorias_menu as $cat): ?>
                <a href="tienda.php?categoria=<?= $cat['id_categoria'] ?>" class="mx-2 nav-link d-inline-block p-0 text-white fw-bold">
                    <?= htmlspecialchars($cat['nombre']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
