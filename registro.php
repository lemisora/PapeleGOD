<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $rol = $_POST['rol'];  // Obtener el rol seleccionado

    // Validar que las contraseñas coinciden
    if ($password != $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } else {
        // Hashear la contraseña
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Insertar el usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, email, usuario, password_hash, id_rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $nombre, $email, $usuario, $password_hash, $rol);

        if ($stmt->execute()) {
            // Registro exitoso, redirigir al login
            header('Location: login.php');
            exit();
        } else {
            $error = "Hubo un error al registrar el usuario.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Papelería</title>
    <!-- Agregar Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body{
            background: linear-gradient(135deg, #6c757d, #343a40);
            background-size: cover;
        }
    </style>
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <div class="container">
            <h1 class="text-center">Papelería</h1>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title text-center">Registro de Usuario</h2>

                        <!-- Mostrar mensajes de error -->
                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?= $error ?>
                            </div>
                        <?php } ?>

                        <form action="registro.php" method="post">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" id="usuario" name="usuario" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirmar Contraseña</label>
                                <input type="password" id="confirm-password" name="confirm-password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <select id="rol" name="rol" class="form-select" required>
                                    
                                    <option value="2">Trabajador</option>
                                    <option value="3">Vendedor</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="login.php" class="text-decoration-none">¿Ya tienes una cuenta? Inicia sesión aquí</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-3 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 Papelería. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Agregar Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
