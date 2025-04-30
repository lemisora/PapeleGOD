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
    <title>Interfaz del Cajero</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para los íconos -->
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
        <h1>Bienvenido, Cajero</h1>
        <p>Selecciona una opción para continuar</p>
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
                    <li class="nav-item"><a class="nav-link" href="perfilc.php"><i class="fas fa-user"></i> Ver Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container my-5">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Tarjeta Ver Promociones -->
            <div class="col">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-tags fa-2x mb-3 text-warning"></i></h5>
                        <h4>Ver Promociones</h4>
                        <p>Accede y consulta las promociones disponibles.</p>
                        <a href="promocionesc.php" class="btn btn-warning">Ir a Promociones</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Ver Productos -->
            <div class="col">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-boxes fa-2x mb-3 text-info"></i></h5>
                        <h4>Ver Productos</h4>
                        <p>Consulta el inventario de productos disponibles.</p>
                        <a href="gproductoscajero.php" class="btn btn-info">Ir a Productos</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Punto de Venta -->
            <div class="col">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-shopping-cart fa-2x mb-3 text-success"></i></h5>
                        <h4>Punto de Venta</h4>
                        <p>Registra las ventas de productos.</p>
                        <a href="puntodeventa.php" class="btn btn-success">Ir al POS</a>
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
