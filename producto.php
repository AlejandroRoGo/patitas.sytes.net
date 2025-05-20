<?php
// producto.php - Página de detalle de producto
require_once 'db_config.php';
$id_producto = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_producto <= 0) {
    header('Location: tienda.php');
    exit;
}
// Cargar producto
$sql = "SELECT p.*, c.nombre AS categoria FROM productos p JOIN categorias c ON p.id_categoria = c.id_categoria WHERE p.id_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_producto);
$stmt->execute();
$res = $stmt->get_result();
$producto = $res->fetch_assoc();
$stmt->close();
if (!$producto) {
    header('Location: tienda.php');
    exit;
}
// Productos aleatorios
$aleatorios = [];
$sql = "SELECT id_producto, nombre, precio, imagen FROM productos WHERE id_producto != ? ORDER BY RAND() LIMIT 2";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_producto);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $aleatorios[] = $row;
$stmt->close();
include 'header.php';
?><meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4 mb-5">
    <div class="row g-4">
        <div class="col-12 col-md-5 text-center">
            <img src="img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="img-fluid rounded mb-3" style="max-height:320px;">
        </div>
        <div class="col-12 col-md-7">
            <h2><?= htmlspecialchars($producto['nombre']) ?></h2>
            <div class="mb-2 text-muted">Categoría: <?= htmlspecialchars($producto['categoria']) ?></div>
            <div class="product-price mb-3">€<?= number_format($producto['precio'],2) ?></div>
            <div class="mb-3">Stock: <span class="fw-bold"><?= $producto['stock'] ?></span></div>
            <div class="mb-4 product-desc"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></div>
            <form method="post" action="agregar_carrito.php" class="d-flex align-items-center gap-2">
                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                <input type="number" name="cantidad" value="1" min="1" max="<?= $producto['stock'] ?>" class="form-control" style="width:100px;">
                <button type="submit" class="btn btn-comprar">Agregar al carrito</button>
            </form>
        </div>
    </div>
    <!-- Productos aleatorios -->
    <div class="row mt-5">
        <h4 class="mb-3">También te puede interesar</h4>
        <?php foreach ($aleatorios as $prod): ?>
        <div class="col-12 col-md-6 mb-3">
            <div class="product-card h-100 d-flex flex-row align-items-center">
                <img src="img/<?= htmlspecialchars($prod['imagen']) ?>" class="product-image me-3" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                <div>
                    <div class="product-title mb-1"><?= htmlspecialchars($prod['nombre']) ?></div>
                    <div class="product-price mb-2">€<?= number_format($prod['precio'],2) ?></div>
                    <a href="producto.php?id=<?= $prod['id_producto'] ?>" class="btn btn-outline-secondary btn-sm">Ver producto</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php if (isset($_GET['addcart']) && $_GET['addcart'] === 'ok'): ?>
<script>
window.addEventListener('DOMContentLoaded', function() {
  let popup = document.createElement('div');
  popup.id = 'popup-carrito-exito';
  popup.innerHTML = `
    <div class="popup-carrito-exito-content bg-success text-white rounded shadow-lg p-4 position-fixed top-50 start-50 translate-middle" style="z-index:2000; min-width:320px; max-width:90vw; text-align:center;">
      <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2" aria-label="Cerrar" onclick="this.closest('#popup-carrito-exito').remove();"></button>
      <div style="font-size:2.2rem;">✅</div>
      <div class="fw-bold fs-5 mb-2">¡Producto añadido al carrito!</div>
      <div class="mb-3">Puedes seguir comprando o ir al carrito para finalizar tu compra.</div>
      <a href="carrito.php" class="btn btn-warning me-2">Ir al carrito</a>
    </div>
    <div class="popup-carrito-exito-backdrop position-fixed top-0 start-0 w-100 h-100" style="background:rgba(0,0,0,0.15);z-index:1999;" onclick="document.getElementById('popup-carrito-exito').remove();"></div>
  `;
  popup.style.position = 'fixed';
  popup.style.top = '0';
  popup.style.left = '0';
  popup.style.width = '100vw';
  popup.style.height = '100vh';
  popup.style.zIndex = '2000';
  document.body.appendChild(popup);
  // Cerrar con Escape
  document.addEventListener('keydown', function esc(e) {
    if (e.key === 'Escape') {
      let el = document.getElementById('popup-carrito-exito');
      if (el) el.remove();
      document.removeEventListener('keydown', esc);
    }
  });
});
</script>
<?php endif; ?>
<?php include 'footer.php'; ?>
