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

// Consultar la base de datos para obtener los datos del usuario
$query = "SELECT u.nombre, u.email, r.nombre AS rol 
          FROM usuarios u 
          INNER JOIN roles r ON u.id_rol = r.id_rol 
          WHERE u.id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($nombre, $email, $rol);

if (!$stmt->fetch()) {
    header('Location: login.php');
    exit();
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Asegura que el contenido principal se expanda y el footer se mantenga al fondo */
        html, body {
            height: 100%; /* Establece la altura de la página al 100% */
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1; /* Esto hace que el contenedor ocupe todo el espacio disponible */
        }

        footer {
            margin-top: auto; /* Esto asegura que el footer se quede al final */
        }

        .card-profile {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .card-profile .icon {
            font-size: 80px; /* Ajusta el tamaño del icono */
            color: #007bff; /* Color personalizado para el icono */
            background-color: #f8f9fa;
            border-radius: 50%;
            padding: 20px;
            margin-right: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-profile h2 {
            margin-bottom: 20px;
        }

        .card-profile .role {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .btn-custom {
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #28a745;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Perfil de Usuario</h1>
    </header>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Papelería</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="promocionesc.php"><i class="fas fa-tags"></i> Promociones y Descuentos</a></li>
                    <li class="nav-item"><a class="nav-link" href="gproductoscajero.php"><i class="fas fa-boxes"></i> Gestión de Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="puntodeventa.php"><i class="fas fa-shopping-cart"></i> Punto de Venta</a></li>
                    <li class="nav-item"><a class="nav-link active" href="perfilc.php"><i class="fas fa-user"></i> Ver Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Información del usuario -->
        <div class="card shadow-lg border-light card-profile">
            <div class="card-body">
                <!-- Icono en lugar de imagen -->
                <div class="icon">
                    <i class="fas fa-user"></i> <!-- Icono de usuario -->
                </div>
                <div>
                    <h2><?php echo htmlspecialchars($nombre); ?></h2>
                    <p class="role"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($rol); ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($email); ?></p>
                    <div class="d-flex justify-content-center mt-4">
                        <a href="actualizarc.php" class="btn btn-success btn-custom mx-2">Actualizar Datos</a>
                        <a href="logout.php" class="btn btn-danger btn-custom mx-2">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- Footer -->
     <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 Papelería. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
