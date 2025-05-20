<?php
// tienda.php - Página de tienda con filtros y listado de productos
require_once 'db_config.php';
include 'header.php';

// --- Filtros ---
$categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$stock = isset($_GET['stock']) ? intval($_GET['stock']) : 0;
$precio_min = isset($_GET['precio_min']) ? floatval($_GET['precio_min']) : 0;
$precio_max = isset($_GET['precio_max']) ? floatval($_GET['precio_max']) : 0;
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'nombre_asc';
$por_pagina = isset($_GET['por_pagina']) ? intval($_GET['por_pagina']) : 10;
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
if (!in_array($por_pagina, [5,10,20])) $por_pagina = 10;

// --- Construir consulta de productos ---
$where = [];
$params = [];
$types = '';
if ($categoria > 0) {
    $where[] = 'id_categoria = ?';
    $params[] = $categoria;
    $types .= 'i';
}
if ($stock > 0) {
    $where[] = 'stock >= ?';
    $params[] = $stock;
    $types .= 'i';
}
if ($precio_min > 0) {
    $where[] = 'precio >= ?';
    $params[] = $precio_min;
    $types .= 'd';
}
if ($precio_max > 0) {
    $where[] = 'precio <= ?';
    $params[] = $precio_max;
    $types .= 'd';
}
$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
// Orden
$orden_sql = 'nombre ASC';
switch($orden) {
    case 'precio_asc': $orden_sql = 'precio ASC'; break;
    case 'precio_desc': $orden_sql = 'precio DESC'; break;
    case 'stock_desc': $orden_sql = 'stock DESC'; break;
    case 'stock_asc': $orden_sql = 'stock ASC'; break;
    case 'nombre_desc': $orden_sql = 'nombre DESC'; break;
}
// Paginación
$offset = ($pagina-1)*$por_pagina;
// Total productos
$sql_total = "SELECT COUNT(*) FROM productos $where_sql";
$stmt_total = $conn->prepare($sql_total);
if ($types) $stmt_total->bind_param($types, ...$params);
$stmt_total->execute();
$stmt_total->bind_result($total_productos);
$stmt_total->fetch();
$stmt_total->close();
// Productos
$sql = "SELECT * FROM productos $where_sql ORDER BY $orden_sql LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($types) {
    $params_all = array_merge($params, [$por_pagina, $offset]);
    $stmt->bind_param($types.'ii', ...$params_all);
} else {
    $stmt->bind_param('ii', $por_pagina, $offset);
}
$stmt->execute();
$res = $stmt->get_result();
$productos = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
// Cargar categorías para el filtro
$categorias = [];
$res_cat = $conn->query("SELECT id_categoria, nombre FROM categorias ORDER BY nombre");
if ($res_cat) {
    while ($row = $res_cat->fetch_assoc()) {
        $categorias[] = $row;
    }
}
// --- HTML ---
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Filtros -->
        <aside class="col-12 col-md-3 mb-4">
            <form method="get" class="bg-white rounded shadow p-3 mb-3">
                <h5 class="mb-3">Filtrar</h5>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select name="categoria" id="categoria" class="form-select">
                        <option value="0">Todas</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>" <?= $categoria==$cat['id_categoria']?'selected':'' ?>><?= htmlspecialchars($cat['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock mínimo</label>
                    <input type="number" min="0" name="stock" id="stock" class="form-control" value="<?= $stock ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio (€)</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" min="0" step="0.01" name="precio_min" class="form-control" placeholder="Mín" value="<?= $precio_min ?>">
                        <span>-</span>
                        <input type="number" min="0" step="0.01" name="precio_max" class="form-control" placeholder="Máx" value="<?= $precio_max ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="orden" class="form-label">Ordenar por</label>
                    <select name="orden" id="orden" class="form-select">
                        <option value="nombre_asc" <?= $orden=='nombre_asc'?'selected':'' ?>>Nombre A-Z</option>
                        <option value="nombre_desc" <?= $orden=='nombre_desc'?'selected':'' ?>>Nombre Z-A</option>
                        <option value="precio_asc" <?= $orden=='precio_asc'?'selected':'' ?>>Precio menor</option>
                        <option value="precio_desc" <?= $orden=='precio_desc'?'selected':'' ?>>Precio mayor</option>
                        <option value="stock_asc" <?= $orden=='stock_asc'?'selected':'' ?>>Stock menor</option>
                        <option value="stock_desc" <?= $orden=='stock_desc'?'selected':'' ?>>Stock mayor</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning w-100">Aplicar filtros</button>
            </form>
        </aside>
        <!-- Productos -->
        <section class="col-12 col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Productos</h4>
                <form method="get" class="d-flex align-items-center gap-2">
                    <?php foreach(['categoria','stock','precio_min','precio_max','orden'] as $f): if(isset($_GET[$f])): ?>
                        <input type="hidden" name="<?= $f ?>" value="<?= htmlspecialchars($_GET[$f]) ?>">
                    <?php endif; endforeach; ?>
                    <label for="por_pagina" class="me-2">Por página:</label>
                    <select name="por_pagina" id="por_pagina" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="5" <?= $por_pagina==5?'selected':'' ?>>5</option>
                        <option value="10" <?= $por_pagina==10?'selected':'' ?>>10</option>
                        <option value="20" <?= $por_pagina==20?'selected':'' ?>>20</option>
                    </select>
                </form>
            </div>
            <?php if (count($productos) == 0): ?>
                <div class="alert alert-info">No hay productos que coincidan con los filtros.</div>
            <?php else: ?>
                <div class="list-group mb-4">
                    <?php foreach ($productos as $prod): ?>
                    <div class="list-group-item bg-white mb-3 rounded shadow-sm p-3 d-flex flex-column flex-md-row align-items-center gap-3 product-list-item">
                        <div class="flex-shrink-0 text-center" style="min-width:120px;">
                            <img src="img/<?= htmlspecialchars($prod['imagen']) ?>" class="product-image mb-2" alt="<?= htmlspecialchars($prod['nombre']) ?>" style="max-width:110px; max-height:110px;">
                        </div>
                        <div class="flex-grow-1 w-100">
                            <div class="d-flex align-items-center gap-3 w-100 justify-content-between">
                                <a href="producto.php?id=<?= $prod['id_producto'] ?>" class="product-title-link text-decoration-none flex-grow-1 text-start" style="min-width:0;">
                                    <div class="product-title mb-0 text-start" style="font-size:1.3rem; font-weight:bold; line-height:1.1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        <?= htmlspecialchars($prod['nombre']) ?>
                                    </div>
                                </a>
                                <span class="badge bg-secondary fs-6 align-self-center" style="white-space:nowrap;">Stock: <?= $prod['stock'] ?></span>
                            </div>
                            <div class="d-flex align-items-center gap-3 mt-2 w-100">
                                <span class="product-price fs-5 flex-shrink-0">€<?= number_format($prod['precio'],2) ?></span>
                                <form method="post" action="agregar_carrito.php" class="d-flex flex-row align-items-center gap-2 mb-0 flex-grow-1" style="width:100%;">
                                    <input type="hidden" name="id_producto" value="<?= $prod['id_producto'] ?>">
                                    <input type="number" name="cantidad" value="1" min="1" max="<?= $prod['stock'] ?>" class="form-control form-control-lg fw-bold text-center" style="max-width:120px; min-width:70px; height:48px; font-size:1.2rem; flex:1 1 80px;">
                                    <button type="submit" class="btn btn-comprar btn-lg fw-bold" style="height:48px; font-size:1.2rem; flex:2 1 120px; min-width:100px;">Agregar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Paginación -->
                <?php
                $total_paginas = ceil($total_productos / $por_pagina);
                if ($total_paginas > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-end">
                        <?php for ($i=1; $i<=$total_paginas; $i++): ?>
                            <li class="page-item <?= $i==$pagina?'active':'' ?>">
                                <a class="page-link" href="?<?php
                                    $q = $_GET;
                                    $q['pagina'] = $i;
                                    echo http_build_query($q);
                                ?>"> <?= $i ?> </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </section>
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
