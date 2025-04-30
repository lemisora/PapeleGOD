<?php
// Incluir la conexión a la base de datos
include 'conexion.php';
session_start();

// Si ya está logueado, redirigir a la página correspondiente
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 1:
            header('Location: administrador.php');
            break;
        case 2:
            header('Location: interfaztrabajador.php');
            break;
        case 3:
            header('Location: interfazcajero.php');
            break;
        default:
            $error = "Rol desconocido, no se puede redirigir.";
            break;
    }
    exit();
}

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consultar la base de datos para verificar las credenciales del usuario
    $sql = "SELECT id_usuario, password_hash, id_rol FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['role'] = $user['id_rol'];
            
            switch ($_SESSION['role']) {
                case 1:
                    header('Location: administrador.php');
                    break;
                case 2:
                    header('Location: interfaztrabajador.php');
                    break;
                case 3:
                    header('Location: interfazcajero.php');
                    break;
                default:
                    $error = "Rol desconocido.";
                    break;
            }
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #6c757d, #343a40);
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            animation: fadeIn 1s ease-in-out;
            width: 100%;
            max-width: 400px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="card p-4 shadow-lg bg-light">
        <h2 class="text-center mb-4">Iniciar Sesión</h2>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
            </div>
        <?php } ?>
        <form action="login.php" method="post" id="loginForm">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100" id="loginBtn">Iniciar Sesión</button>
            <div class="text-center mt-3">
                <a href="recuperarcontrasena.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a><br>
                <a href="registro.php" class="text-decoration-none">Registrarse</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS y Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Validación y Funcionalidades -->
    <script>
        // Mostrar/Ocultar contraseña
        function togglePassword() {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }

        // Indicador de carga al iniciar sesión
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        loginForm.addEventListener('submit', function(e) {
            if (document.getElementById('usuario').value.trim() === '' || document.getElementById('password').value.trim() === '') {
                e.preventDefault();
                alert('Por favor, completa todos los campos.');
                return;
            }
            loginBtn.textContent = 'Cargando...';
            loginBtn.disabled = true;
        });
    </script>
</body>
</html>
