<?php
// db_config.php - Configuración de conexión a la base de datos

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';          // contraseña por defecto vacía en XAMPP
$DB_NAME = 'bd_patitas';

// Crear conexión MySQLi
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Establecer conjunto de caracteres (para ñ, acentos)
$conn->set_charset("utf8");

// Nota: Este archivo se incluye en otros scripts PHP que necesiten acceder a la BBDD.
//       Al usar 'require', la variable $conn estará disponible para realizar consultas.
?>
