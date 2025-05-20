<?php
// export_catalog.php - Exporta el catálogo de productos en XML
require_once 'db_config.php';
header('Content-Type: application/xml; charset=utf-8');
header('Content-Disposition: attachment; filename="catalogo_patitas_'.date('Ymd').'.xml"');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<catalogo fecha='".date('Y-m-d')."'>\n";
$sql = "SELECT p.*, c.nombre AS categoria FROM productos p JOIN categorias c ON p.id_categoria = c.id_categoria ORDER BY p.id_producto";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    echo "  <producto id='".$row['id_producto']."'>\n";
    echo "    <nombre>".htmlspecialchars($row['nombre'])."</nombre>\n";
    echo "    <descripcion>".htmlspecialchars($row['descripcion'])."</descripcion>\n";
    echo "    <precio>".number_format($row['precio'],2,'.','')."</precio>\n";
    echo "    <stock>".$row['stock']."</stock>\n";
    echo "    <categoria>".htmlspecialchars($row['categoria'])."</categoria>\n";
    echo "    <imagen>".htmlspecialchars($row['imagen'])."</imagen>\n";
    echo "  </producto>\n";
}
echo "</catalogo>\n";
exit;
