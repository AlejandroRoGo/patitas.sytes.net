<?php
// index.php - Página de inicio principal
require_once 'db_config.php';
// Cargar categorías (con imagen) ordenadas por id
$categorias = [];
$sql_cat = "SELECT id_categoria, nombre, imagenes FROM categorias ORDER BY id_categoria";
$res_cat = $conn->query($sql_cat);
if ($res_cat) {
    while ($row = $res_cat->fetch_assoc()) {
        $categorias[] = $row;
    }
}
// Cargar productos destacados (4 aleatorios)
$productos = [];
$sql_prod = "SELECT * FROM productos ORDER BY RAND() LIMIT 4";
$res_prod = $conn->query($sql_prod);
if ($res_prod) {
    while ($row = $res_prod->fetch_assoc()) {
        $productos[] = $row;
    }
}
// Incluir header
include 'header.php';
?>
<!-- Meta viewport para responsividad móvil -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
/* Asegura que el carrusel ocupe el 100% del viewport */
#carouselDestacados.carousel {
  width: 100vw !important;
  margin-left: calc(-50vw + 50%);
}
.carousel-inner, .carousel-item {
  width: 100vw !important;
}
@media (max-width: 767.98px) {
  #carouselDestacados .carousel-item .list-group-item {
    flex-direction: column !important;
    height: auto !important;
    min-height: 120px !important;
    max-height: none !important;
    padding: 1.2rem 0.5rem !important;
  }
  #carouselDestacados .carousel-item .product-img-col {
    width: 80vw !important;
    height: 32vw !important;
    max-width: 180px !important;
    max-height: 180px !important;
    margin-bottom: 0.5rem;
  }
  #carouselDestacados .carousel-item .product-title {
    font-size: 1.1rem !important;
  }
  #carouselDestacados .carousel-item .product-desc {
    font-size: 0.95rem !important;
    max-width: 90vw !important;
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: initial !important;
    display: block !important;
  }
  #carouselDestacados .carousel-item .d-flex.gap-2 {
    flex-direction: row !important;
    justify-content: center !important;
    width: 100%;
  }
  #carouselDestacados .carousel-item .product-info-col {
    flex-direction: column !important;
    align-items: center !important;
    gap: 0.5rem !important;
    padding: 0 !important;
  }
}
</style>
<div class="container-fluid px-0 mt-4"> <!-- Cambiado a container-fluid y sin padding -->
    <!-- Carrusel de productos destacados con animación y swipe -->
    <div class="row mb-4 gx-0">
        <div class="col-12 px-0">
            <h2 class="mb-3 text-center">Productos Destacados</h2>
            <div id="carouselDestacados" class="carousel slide carousel-dark w-100" data-bs-ride="carousel" data-bs-touch="true" style="width:100vw;max-width:100vw;margin-left:calc(-50vw + 50%);">
                <div class="carousel-inner w-100">
                    <?php foreach ($productos as $i => $prod): ?>
                    <div class="carousel-item <?= $i === 0 ? 'active' : '' ?> w-100">
                        <div class="list-group-item bg-white mb-3 rounded-4 shadow p-2 d-flex flex-row align-items-center justify-content-center product-list-item mx-auto"
                             style="width:98vw; max-width:1200px; min-height:120px; height:clamp(90px,16vw,160px); max-height:180px; border-radius:2rem; box-shadow:0 2px 12px rgba(0,0,0,0.07);">
                            <div class="flex-shrink-0 d-flex flex-column align-items-center justify-content-center product-img-col"
                                 style="min-width:80px; width:18vw; height:18vw; max-width:120px; max-height:120px;">
                                <img src="img/<?= htmlspecialchars($prod['imagen']) ?>" class="product-image mb-1" alt="<?= htmlspecialchars($prod['nombre']) ?>"
                                     style="max-width:90%; max-height:90%; object-fit:contain;">
                            </div>
                            <div class="flex-grow-1 d-flex flex-row align-items-center justify-content-between px-2 gap-2 product-info-col" style="min-width:0;">
                                <div class="d-flex flex-column align-items-start justify-content-center w-100" style="min-width:0;">
                                    <div class="d-flex align-items-center gap-3 w-100" style="min-width:0;">
                                        <a href="producto.php?id=<?= $prod['id_producto'] ?>" class="product-title-link text-decoration-none flex-grow-1 text-start" style="min-width:0;">
                                            <div class="product-title mb-0 text-start" style="font-size:1.3rem; font-weight:bold; line-height:1.1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                <?= htmlspecialchars($prod['nombre']) ?>
                                            </div>
                                        </a>
                                        <span class="badge bg-secondary fs-6 align-self-start mt-1" style="height:fit-content;">Stock: <?= $prod['stock'] ?></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mt-2 w-100">
                                        <span class="product-price fs-5 flex-shrink-0">€<?= number_format($prod['precio'],2) ?></span>
                                        <form method="post" action="agregar_carrito.php" class="d-flex align-items-center gap-2 mb-0 flex-grow-1" style="min-width:0;">
                                            <input type="hidden" name="id_producto" value="<?= $prod['id_producto'] ?>">
                                            <input type="number" name="cantidad" value="1" min="1" max="<?= $prod['stock'] ?>" class="form-control form-control-lg fw-bold text-center" style="max-width:110px; min-width:70px; height:56px; font-size:1.3rem; flex:1 1 0;">
                                            <button type="submit" class="btn btn-comprar btn-lg fw-bold" style="height:56px; font-size:1.2rem; flex:2 1 0; min-width:120px;">Agregar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselDestacados" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselDestacados" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
                <div class="carousel-indicators">
                    <?php foreach ($productos as $i => $prod): ?>
                        <button type="button" data-bs-target="#carouselDestacados" data-bs-slide-to="<?= $i ?>" class="<?= $i===0?'active':'' ?>" aria-current="<?= $i===0?'true':'false' ?>" aria-label="Slide <?= $i+1 ?>"></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Discos de categorías -->
    <div class="row mb-5 justify-content-center">
        <h3 class="mb-4 text-center">Categorías</h3>
        <?php $col = count($categorias) > 0 ? floor(12/count($categorias)) : 3; ?>
        <?php foreach ($categorias as $cat): ?>
        <div class="col-6 col-md-3 col-lg-3 mb-3 d-flex flex-column align-items-center justify-content-center">
            <a href="tienda.php?categoria=<?= $cat['id_categoria'] ?>" class="text-decoration-none w-100">
                <div class="rounded-circle bg-white shadow d-flex align-items-center justify-content-center overflow-hidden mb-2 mx-auto" style="width:180px; height:180px; max-width:95vw;">
                    <img src="img/<?= htmlspecialchars($cat['imagenes']) ?>" alt="<?= htmlspecialchars($cat['nombre']) ?>" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div class="fw-bold text-dark text-center" style="font-size:1.2rem;line-height:1.2;">
                    <?= htmlspecialchars($cat['nombre']) ?>
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
<script>
// Hacer el carrusel deslizable con el ratón (drag/swipe)
(function() {
    const carousel = document.getElementById('carouselDestacados');
    let startX = 0;
    let isDown = false;
    let threshold = 50; // px para considerar swipe
    if (carousel) {
        carousel.addEventListener('mousedown', function(e) {
            isDown = true;
            startX = e.clientX;
        });
        carousel.addEventListener('mouseup', function(e) {
            if (!isDown) return;
            let diff = e.clientX - startX;
            if (diff > threshold) {
                // Swipe derecha
                bootstrap.Carousel.getOrCreateInstance(carousel).prev();
            } else if (diff < -threshold) {
                // Swipe izquierda
                bootstrap.Carousel.getOrCreateInstance(carousel).next();
            }
            isDown = false;
        });
        carousel.addEventListener('mouseleave', function() { isDown = false; });
        // Touch events para móvil
        let touchStartX = 0;
        carousel.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) touchStartX = e.touches[0].clientX;
        });
        carousel.addEventListener('touchend', function(e) {
            let touchEndX = e.changedTouches[0].clientX;
            let diff = touchEndX - touchStartX;
            if (diff > threshold) {
                bootstrap.Carousel.getOrCreateInstance(carousel).prev();
            } else if (diff < -threshold) {
                bootstrap.Carousel.getOrCreateInstance(carousel).next();
            }
        });
    }
})();
</script>
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
