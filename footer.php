<?php
// footer.php - Pie de página común
?>
<footer class="mt-5">
    <div class="container">
        <div class="row align-items-center mb-3">
            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <span class="fw-bold">Síguenos:</span>
                <a href="https://facebook.com/" target="_blank" rel="noopener" class="ms-2"><img src="img/facebook.png" alt="Facebook" style="height:24px;"></a>
                <a href="https://instagram.com/" target="_blank" rel="noopener" class="ms-2"><img src="img/instagram.png" alt="Instagram" style="height:24px;"></a>
                <a href="https://x.com/" target="_blank" rel="noopener" class="ms-2"><img src="img/x.png" alt="X" style="height:24px;"></a>
            </div>
            <div class="col-12 col-md-4 mb-2 mb-md-0 text-center">
                <a href="https://www.google.com/maps/place/Madrid" target="_blank" rel="noopener" class="text-decoration-underline text-light">Ver en el mapa</a>
            </div>
            <div class="col-12 col-md-4 text-md-end text-center">
                <a href="export_catalog.php" class="btn btn-sm btn-outline-warning">Descargar catálogo XML</a>
            </div>
        </div>
        <div class="text-center text-secondary small">&copy; <?= date('Y') ?> Patitas. Todos los derechos reservados.</div>
    </div>
</footer>
