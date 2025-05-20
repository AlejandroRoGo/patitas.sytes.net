<?php
// login.php - Página de inicio de sesión
require_once 'db_config.php';
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $sql = "SELECT id_usuario, password FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($id_usuario, $hash);
        if ($stmt->fetch() && password_verify($password, $hash)) {
            $_SESSION['id_usuario'] = $id_usuario;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Email o contraseña incorrectos.';
        }
        $stmt->close();
    } else {
        $error = 'Rellena todos los campos.';
    }
}
include 'header.php';
?>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-5 mb-5" style="max-width:400px;">
    <div class="bg-white rounded shadow p-4">
        <h2 class="mb-4 text-center">Iniciar sesión</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Entrar</button>
        </form>
        <div class="mt-3 text-center">
            <a href="register.php">¿No tienes cuenta? Regístrate</a>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
