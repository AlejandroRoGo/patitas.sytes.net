<?php
// index.php - Página de inicio principal
require_once 'db_config.php';
// Cargar categorías
$categorias = [];
$sql_cat = "SELECT id_categoria, nombre FROM categorias ORDER BY nombre";
$res_cat = $conn->query($sql_cat);
if ($res_cat) {
    while ($row = $res_cat->fetch_assoc()) {
        $categorias[] = $row;
    }
}
// Cargar productos destacados (los 4 primeros)
$productos = [];
$sql_prod = "SELECT * FROM productos ORDER BY id_producto LIMIT 4";
$res_prod = $conn->query($sql_prod);
if ($res_prod) {
    while ($row = $res_prod->fetch_assoc()) {
        $productos[] = $row;
    }
}
// Incluir header
include 'header.php';
?>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4">
    <!-- Carrusel de productos destacados (sin animación JS) -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3 text-center">Productos Destacados</h2>
            <div class="row justify-content-center">
                <?php foreach ($productos as $prod): ?>
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <div class="product-card h-100">
                        <img src="img/<?= htmlspecialchars($prod['imagen']) ?>" class="product-image mb-2" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                        <div class="product-title"><?= htmlspecialchars($prod['nombre']) ?></div>
                        <div class="product-price">€<?= number_format($prod['precio'],2) ?></div>
                        <a href="producto.php?id=<?= $prod['id_producto'] ?>" class="btn btn-comprar mb-2">Ver producto</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Discos de categorías -->
    <div class="row mb-5 justify-content-center">
        <h3 class="mb-4 text-center">Categorías</h3>
        <?php foreach ($categorias as $cat): ?>
        <div class="col-6 col-md-3 mb-3 d-flex justify-content-center">
            <a href="tienda.php?categoria=<?= $cat['id_categoria'] ?>" class="text-decoration-none">
                <div class="rounded-circle bg-white shadow p-4 d-flex flex-column align-items-center" style="width:120px; height:120px;">
                    <span class="fs-3 mb-2 text-primary"><i class="bi bi-tags"></i></span>
                    <span class="fw-bold text-dark text-center"><?= htmlspecialchars($cat['nombre']) ?></span>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <!-- Sobre nosotros -->
    <div class="row mb-5">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="bg-white rounded shadow p-4">
                <h3 class="mb-3">Sobre Nosotros</h3>
                <p>En Patitas nos apasionan los animales y el planeta. Ofrecemos productos ecológicos y sostenibles para todo tipo de mascotas, seleccionados con el máximo cuidado para garantizar su bienestar y el tuyo. Nuestro compromiso es la calidad, la atención personalizada y el respeto al medio ambiente. ¡Gracias por confiar en nosotros!</p>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
