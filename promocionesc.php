<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Consulta para obtener todos los productos disponibles
$sqlProductos = "SELECT id_producto, nombre FROM productos";
$resultProductos = $conn->query($sqlProductos);

// Almacenar los productos
$productos = [];
if ($resultProductos->num_rows > 0) {
    while ($row = $resultProductos->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Consulta para obtener todas las promociones activas, incluyendo el nombre del producto
$sqlPromociones = "SELECT p.nombre AS producto_nombre, pr.descuento, pr.fecha_inicio, pr.fecha_fin, pr.id_promocion 
                   FROM promociones pr 
                   JOIN productos p ON pr.producto_id = p.id_producto 
                   WHERE pr.activo = 1";
$resultPromociones = $conn->query($sqlPromociones);

// Almacenar las promociones con el nombre del producto
$promociones = [];
if ($resultPromociones->num_rows > 0) {
    while ($row = $resultPromociones->fetch_assoc()) {
        $promociones[] = $row;
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones y Descuentos - Papelería</title>
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

    <header class="bg-dark text-white text-center py-4">
        <h1>Gestión de Promociones y Descuentos</h1>
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
                    <li class="nav-item"><a class="nav-link active" href="promocionesc.php"><i class="fas fa-tags"></i> Promociones y Descuentos</a></li>
                    <li class="nav-item"><a class="nav-link" href="gproductoscajero.php"><i class="fas fa-boxes"></i> Gestión de Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="puntodeventa.php"><i class="fas fa-shopping-cart"></i> Punto de Venta</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfilc.php"><i class="fas fa-user"></i> Ver Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        <!-- Tabla de promociones -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Descuento</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                </tr>
            </thead>
            <tbody id="tabla-promociones">
    <?php foreach ($promociones as $promocion): ?>
        <tr data-id="<?php echo $promocion['id_promocion']; ?>">
            <td><?php echo htmlspecialchars($promocion['producto_nombre']); ?></td>  <!-- Nombre del producto -->
            <td><?php echo htmlspecialchars($promocion['descuento']); ?>%</td>
            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($promocion['fecha_inicio']))); ?></td>
            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($promocion['fecha_fin']))); ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>

        </table>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Papelería. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>
