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
                <div class="product-grid">
                    <?php foreach ($productos as $prod): ?>
                        <div class="product-card">
                            <img src="img/<?= htmlspecialchars($prod['imagen']) ?>" class="product-image mb-2" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                            <div class="product-title"><?= htmlspecialchars($prod['nombre']) ?></div>
                            <div class="product-price">€<?= number_format($prod['precio'],2) ?></div>
                            <div class="mb-2"><span class="badge bg-secondary">Stock: <?= $prod['stock'] ?></span></div>
                            <div class="d-flex gap-2 w-100">
                                <form method="post" action="agregar_carrito.php" class="flex-grow-1">
                                    <input type="hidden" name="id_producto" value="<?= $prod['id_producto'] ?>">
                                    <input type="number" name="cantidad" value="1" min="1" max="<?= $prod['stock'] ?>" class="form-control form-control-sm mb-2" style="max-width:80px;display:inline-block;">
                                    <button type="submit" class="btn btn-comprar w-100">Agregar al carrito</button>
                                </form>
                                <a href="producto.php?id=<?= $prod['id_producto'] ?>" class="btn btn-outline-secondary">+ Detalles</a>
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
<?php include 'footer.php'; ?>
