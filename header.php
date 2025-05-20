<?php
// header.php - Cabecera común para todas las páginas
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'db_config.php';
// Cargar categorías para el menú (ordenadas por id)
$categorias_menu = [];
$sql_cat_menu = "SELECT id_categoria, nombre FROM categorias ORDER BY id_categoria";
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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
  <div class="container-fluid">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="img/logo.png" alt="Patitas" style="height:48px;width:auto;max-width:120px;" class="me-2">
      <span class="logo d-none d-md-inline">Patitas</span>
    </a>
    <!-- Botón hamburguesa -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Menú colapsable -->
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link fw-bold" href="tienda.php">Tienda</a>
        </li>
        <?php foreach ($categorias_menu as $cat): ?>
          <li class="nav-item">
            <a class="nav-link fw-bold" href="tienda.php?categoria=<?= $cat['id_categoria'] ?>">
              <?= htmlspecialchars($cat['nombre']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="d-flex align-items-center ms-lg-3 mt-3 mt-lg-0">
        <?php if (isset($_SESSION['id_usuario'])): ?>
          <a href="carrito.php" class="cart-icon position-relative me-3 d-flex align-items-center">
            <img src="img/carrito.png" alt="Carrito" style="height:28px;width:auto;" class="me-1">
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
  </div>
</nav>
<!-- Bootstrap JS para navbar hamburguesa (solo para el menú, no para lógica de la web) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
