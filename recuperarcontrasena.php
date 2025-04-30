<?php
// Incluir la conexión a la base de datos
include 'conexion.php';
session_start();

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Consultar la base de datos para verificar si el correo existe
    $sql = "SELECT id_usuario, usuario, email FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Enviar un correo al usuario con su nombre de usuario
        $subject = "Recuperación de Contraseña - Papelería";
        $message = "Hola, " . $user['usuario'] . "\n\nRecibimos una solicitud para recuperar tu contraseña.\n\nPor favor, ingresa al siguiente link para restablecerla:\n\n";
        $message .= "http://localhost/papeleria%202.0/reestablecer_contraseña.php"; // Incluye el token en la URL
        $headers = "From: berhdez2003@gmail.com";

        // Enviar correo (Asegúrate de configurar tu servidor)
        mail($email, $subject, $message, $headers);

        $successMessage = "Hemos enviado un correo con instrucciones.";
    } else {
        $error = "El correo electrónico no está registrado.";
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
    <title>Recuperar Contraseña - Papelería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <h1>Papelería</h1>
    </header>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title text-center">Recuperar Contraseña</h2>
                        <p class="text-center">Introduce tu correo electrónico para recibir ayuda.</p>

                        <!-- Mostrar mensajes -->
                        <?php if (isset($successMessage)) { ?>
                            <div class="alert alert-success text-center">
                                <?= $successMessage ?>
                            </div>
                        <?php } elseif (isset($error)) { ?>
                            <div class="alert alert-danger text-center">
                                <?= $error ?>
                            </div>
                        <?php } ?>

                        <!-- Formulario -->
                        <form action="recuperarcontrasena.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Recuperar Contraseña</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="login.php">Volver a Iniciar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-3 text-center">
        <p>&copy; 2024 Papelería. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
