<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Incluir el archivo de conexión a la base de datos
include('conexion.php');

// Obtener el id del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Consultar la base de datos para obtener los datos actuales del usuario
$query = "SELECT u.nombre, u.email, u.usuario 
          FROM usuarios u 
          WHERE u.id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($nombre, $email, $usuario);

if (!$stmt->fetch()) {
    // Si no se encuentran datos del usuario, redirigir al login
    header('Location: login.php');
    exit();
}
$stmt->close();

// Verificar si el formulario fue enviado para actualizar los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre_nuevo = $_POST['nombre'];
    $email_nuevo = $_POST['email'];
    $usuario_nuevo = $_POST['usuario'];
    $password_nueva = $_POST['password'];
    $password_confirmada = $_POST['confirm_password'];

    // Validaciones simples
    $errores = [];
    if (empty($nombre_nuevo)) {
        $errores[] = 'El nombre no puede estar vacío.';
    }
    if (empty($email_nuevo) || !filter_var($email_nuevo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electrónico no es válido.';
    }
    if (empty($usuario_nuevo)) {
        $errores[] = 'El nombre de usuario no puede estar vacío.';
    }
    if (!empty($password_nueva) && $password_nueva !== $password_confirmada) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    // Validar que el nombre de usuario no esté duplicado
    if (empty($errores)) {
        $query_usuario = "SELECT id_usuario FROM usuarios WHERE usuario = ? AND id_usuario != ?";
        $stmt = $conn->prepare($query_usuario);
        $stmt->bind_param("si", $usuario_nuevo, $user_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errores[] = 'El nombre de usuario ya está en uso.';
        }
        $stmt->close();
    }

    // Si no hay errores, actualizar los datos en la base de datos
    if (empty($errores)) {
        // Actualizar la contraseña si se proporciona
        if (!empty($password_nueva)) {
            $password_nueva = password_hash($password_nueva, PASSWORD_BCRYPT);
            $update_query = "UPDATE usuarios SET nombre = ?, email = ?, usuario = ?, password = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssssi", $nombre_nuevo, $email_nuevo, $usuario_nuevo, $password_nueva, $user_id);
        } else {
            $update_query = "UPDATE usuarios SET nombre = ?, email = ?, usuario = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sssi", $nombre_nuevo, $email_nuevo, $usuario_nuevo, $user_id);
        }

        if ($stmt->execute()) {
            // Si la actualización es exitosa, redirigir al perfil
            header('Location: perfil.php');
            exit();
        } else {
            $errores[] = 'Error al actualizar los datos. Intenta nuevamente.';
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Perfil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Actualizar Perfil</h1>
    </header>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="interfazcajero.php">Papelería</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="perfilc.php"><i class="fas fa-user"></i> Ver Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5 form-container">
        <h2>Actualiza tus datos</h2>

        <!-- Mostrar errores si los hay -->
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña (opcional)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar nueva contraseña (opcional)</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="perfilc.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 Papelería. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
