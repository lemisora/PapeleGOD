<?php
// Incluir la conexión a la base de datos
include 'conexion.php';
session_start();

$errorMessage = $successMessage = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    if (empty($newPassword)) {
        $errorMessage = "Por favor ingresa una nueva contraseña.";
    } elseif (strlen($newPassword) < 8) {
        $errorMessage = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        // Actualizar la contraseña en la base de datos
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE usuarios SET password_hash = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $hashedPassword, $email);

        if ($stmt->execute()) {
            $successMessage = "Tu contraseña ha sido actualizada con éxito.";
        } else {
            $errorMessage = "Hubo un error al actualizar tu contraseña.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Papelería</title>
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
                        <h2 class="card-title text-center">Restablecer Contraseña</h2>
                        <p class="text-center">Ingresa tu nueva contraseña.</p>

                        <!-- Mostrar mensajes -->
                        <?php if (!empty($successMessage)) { ?>
                            <div class="alert alert-success text-center">
                                <?= $successMessage ?>
                            </div>
                        <?php } elseif (!empty($errorMessage)) { ?>
                            <div class="alert alert-danger text-center">
                                <?= $errorMessage ?>
                            </div>
                        <?php } ?>

                        <!-- Formulario -->
                        <form action="reestablecer_contraseña.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nueva Contraseña</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Restablecer Contraseña</button>
                        </form>
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
