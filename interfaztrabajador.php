<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Trabajador</title>
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
    </style>
</head>
<body>
    <!-- Encabezado -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Panel del Trabajador</h1>
    </header>

    <!-- Barra de navegación del trabajador -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="interfaztrabajador.php">Papelería</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="gcajast.php"><i class="fas fa-cash-register"></i> Registro de Ventas</a></li>
                    <li class="nav-item"><a class="nav-link" href="gproductostrabajador.php"><i class="fas fa-box"></i> Ver Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfil.php"><i class="fas fa-user"></i> Ver Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container my-5">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Tarjeta Registro de Ventas -->
            <div class="col">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-cash-register fa-2x mb-3 text-primary"></i></h5>
                        <h4>Registro de Ventas</h4>
                        <p>Accede al sistema de punto de venta para ver las ventas</p>
                        <a href="gcajast.php" class="btn btn-primary">Ir al Registro de Ventas</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Ver Productos -->
            <div class="col">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-box fa-2x mb-3 text-success"></i></h5>
                        <h4>Ver Productos</h4>
                        <p>Consulta el inventario de productos disponibles.</p>
                        <a href="gproductostrabajador.php" class="btn btn-success">Ir a Ver Productos</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Ver Perfil -->
            <div class="col">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-user fa-2x mb-3 text-info"></i></h5>
                        <h4>Ver Perfil</h4>
                        <p>Consulta tus datos personales y horarios de trabajo.</p>
                        <a href="perfil.php" class="btn btn-info">Ir a Ver Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Papelería. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
