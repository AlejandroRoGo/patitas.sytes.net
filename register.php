<?php
// register.php - Registro de usuario
require_once 'db_config.php';
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    if ($nombre && $apellidos && $email && $password) {
        $sql = "SELECT id_usuario FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'El email ya está registrado.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql2 = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param('ssssss', $nombre, $apellidos, $email, $hash, $telefono, $fecha_nacimiento);
            if ($stmt2->execute()) {
                $_SESSION['id_usuario'] = $stmt2->insert_id;
                header('Location: index.php');
                exit;
            } else {
                $error = 'Error al registrar. Intenta de nuevo.';
            }
            $stmt2->close();
        }
        $stmt->close();
    } else {
        $error = 'Rellena todos los campos obligatorios.';
    }
}
include 'header.php';
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<div class="container mt-5 mb-5" style="max-width:500px;">
    <div class="bg-white rounded shadow p-4">
        <h2 class="mb-4 text-center">Registro</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" novalidate>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre*</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos*</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Contraseña*</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary" tabindex="-1" onclick="togglePassword()" style="border-top-left-radius:0;border-bottom-left-radius:0;">
                        <span id="icon-eye" class="bi bi-eye"></span>
                    </button>
                </div>
            </div>
            <script>
            function togglePassword() {
                const input = document.getElementById('password');
                const icon = document.getElementById('icon-eye');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
            </script>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" pattern="^[0-9]{9,15}$" title="Introduce un teléfono válido (solo números, 9-15 dígitos)" maxlength="15" minlength="9">
                <div class="form-text">Solo números, entre 9 y 15 dígitos.</div>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" max="<?php echo date('Y-m-d'); ?>">
            </div>
            <button type="submit" class="btn btn-warning w-100">Registrarse</button>
        </form>
        <div class="mt-3 text-center">
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
