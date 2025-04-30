<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Consultar todos los productos
$sql = "SELECT id_producto, nombre, stock, precio FROM productos";
$result = $conn->query($sql);

$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Papelería</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
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
        .hidden { display: none; }
        .card-custom { border-radius: 10px; }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Gestión de Productos</h1>
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
                    <li class="nav-item"><a class="nav-link active" href="gproductoscajero.php"><i class="fas fa-boxes"></i> Gestión de Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="puntodeventa.php"><i class="fas fa-shopping-cart"></i> Punto de Venta</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfilc.php"><i class="fas fa-user"></i> Ver Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container my-5">
        

        <!-- Tabla de productos -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        
                    </tr>
                </thead>
                <tbody id="tabla-productos">
                    <?php foreach ($productos as $producto): ?>
                        <tr data-id="<?php echo $producto['id_producto']; ?>">
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo $producto['stock']; ?></td>
                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Papelería. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    
</body>
</html>
