<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Administrador</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Encabezado -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Panel del Administrador</h1>
    </header>

    <!-- Barra de navegación del administrador -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="administrador.php">Papelería</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="gestionproductos.php"><i class="fas fa-boxes"></i> Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestionproveedores.php"><i class="fas fa-truck"></i> Proveedores</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestiontrabajadores.php"><i class="fas fa-users"></i> Trabajadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestioncajas.php"><i class="fas fa-chart-line"></i> Ventas</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestionpromociones.php"><i class="fas fa-tags"></i> Promociones</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfiladmin.php"><i class="fas fa-user"></i> Ver Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container my-5">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Tarjeta Gestión de Productos -->
            <div class="col">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-boxes fa-2x mb-3 text-primary"></i></h5>
                        <h4>Gestión de Productos</h4>
                        <p>Administra el inventario y controla los productos.</p>
                        <a href="gestionproductos.php" class="btn btn-primary">Ir a Productos</a>
                    </div>
                </div>
            </div>
            
            <!-- Tarjeta Gestión de Proveedores -->
            <div class="col">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-truck fa-2x mb-3 text-success"></i></h5>
                        <h4>Gestión de Proveedores</h4>
                        <p>Controla tus proveedores y mantén el stock.</p>
                        <a href="gestionproveedores.php" class="btn btn-success">Ir a Proveedores</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Gestión de Trabajadores -->
            <div class="col">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-users fa-2x mb-3 text-info"></i></h5>
                        <h4>Gestión de Trabajadores</h4>
                        <p>Administra los empleados y sus roles.</p>
                        <a href="gestiontrabajadores.php" class="btn btn-info">Ir a Trabajadores</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Reporte de Ventas -->
            <div class="col">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-chart-line fa-2x mb-3 text-warning"></i></h5>
                        <h4>Reporte de Ventas</h4>
                        <p>Genera reportes para analizar ventas.</p>
                        <a href="gestioncajas.php" class="btn btn-warning">Ir a Ventas</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Promociones -->
            <div class="col">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-tags fa-2x mb-3 text-danger"></i></h5>
                        <h4>Promociones y Descuentos</h4>
                        <p>Crea y gestiona promociones especiales.</p>
                        <a href="gestionpromociones.php" class="btn btn-danger">Ir a Promociones</a>
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
                        <a href="perfiladmin.php" class="btn btn-info">Ir a Ver Perfil</a>
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
