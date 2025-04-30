<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Función para obtener el total de ventas según el periodo
function obtenerVentas($periodo = 'hoy') {
    global $conn;

    // Definir la consulta según el periodo
    if ($periodo == 'hoy') {
        // Para hoy, solo necesitamos comparar la fecha sin hora
        $sql_ventas = "SELECT SUM(v.total) AS total_ventas 
                       FROM ventas v
                       WHERE DATE(v.fecha_venta) = CURDATE()";
    } elseif ($periodo == 'semana') {
        $fecha_inicio = date('Y-m-d', strtotime('monday this week')) . ' 00:00:00';
        $fecha_fin = date('Y-m-d', strtotime('sunday this week')) . ' 23:59:59';
        $sql_ventas = "SELECT SUM(v.total) AS total_ventas 
                       FROM ventas v
                       WHERE v.fecha_venta BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    } elseif ($periodo == 'mes') {
        $fecha_inicio = date('Y-m-01') . ' 00:00:00';
        $fecha_fin = date('Y-m-t') . ' 23:59:59';
        $sql_ventas = "SELECT SUM(v.total) AS total_ventas 
                       FROM ventas v
                       WHERE v.fecha_venta BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    // Ejecutar la consulta y obtener el total
    $result_ventas = $conn->query($sql_ventas);
    if ($result_ventas->num_rows > 0) {
        $row = $result_ventas->fetch_assoc();
        return $row['total_ventas'] ?? 0;
    }
    return 0;
}

// Función para obtener las ventas y sus detalles para el reporte
function obtenerVentasDetalles($periodo = 'hoy') {
    global $conn;

    // Definir la consulta según el periodo
    if ($periodo == 'hoy') {
        // Para hoy, solo necesitamos comparar la fecha sin hora
        $sql_ventas = "SELECT v.id_venta, v.fecha_venta, v.total, v.metodo_pago, u.nombre AS usuario
                       FROM ventas v
                       INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
                       WHERE DATE(v.fecha_venta) = CURDATE()";
    } elseif ($periodo == 'semana') {
        $fecha_inicio = date('Y-m-d', strtotime('monday this week')) . ' 00:00:00';
        $fecha_fin = date('Y-m-d', strtotime('sunday this week')) . ' 23:59:59';
        $sql_ventas = "SELECT v.id_venta, v.fecha_venta, v.total, v.metodo_pago, u.nombre AS usuario
                       FROM ventas v
                       INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
                       WHERE v.fecha_venta BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    } elseif ($periodo == 'mes') {
        $fecha_inicio = date('Y-m-01') . ' 00:00:00';
        $fecha_fin = date('Y-m-t') . ' 23:59:59';
        $sql_ventas = "SELECT v.id_venta, v.fecha_venta, v.total, v.metodo_pago, u.nombre AS usuario
                       FROM ventas v
                       INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
                       WHERE v.fecha_venta BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    // Ejecutar la consulta y obtener los detalles
    $result_ventas = $conn->query($sql_ventas);
    $ventas = [];
    while ($row = $result_ventas->fetch_assoc()) {
        $ventas[] = $row;
    }

    return $ventas;
}

// Función para generar el archivo CSV
function generarReporteCSV($ventas, $periodo) {
    // Definir el nombre del archivo CSV
    $filename = "reporte_ventas_$periodo_" . date('YmdHis') . ".csv";

    // Configurar los encabezados para la descarga del archivo CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Abrir el archivo para escribir
    $output = fopen('php://output', 'w');

    // Escribir los encabezados del archivo CSV
    fputcsv($output, ['ID Venta', 'Fecha de Venta', 'Total', 'Método de Pago', 'Usuario']);

    // Escribir los datos de las ventas
    foreach ($ventas as $venta) {
        fputcsv($output, $venta);
    }

    // Cerrar el archivo CSV
    fclose($output);
    exit;
}

// Comprobar si se ha solicitado generar el reporte
if (isset($_GET['generar_reporte'])) {
    $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'hoy';
    $ventas = obtenerVentasDetalles($periodo);
    generarReporteCSV($ventas, $periodo);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cajas - Papelería</title>
    <!-- Incluir Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="styles.css">
    <style>
        html, body {
            height: 100%; /* Establece la altura de la página al 100% */
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .container {
            flex: 1; /* Esto hace que el contenedor ocupe todo el espacio disponible */
        }
        .hidden { display: none; }
        .card-custom { border-radius: 10px; }
        footer {
            margin-top: auto; /* Esto asegura que el footer se quede al final */
        }
    </style>
</head>
<body>
    
    <!-- Encabezado -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Gestión de Cajas</h1>
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
                    <li class="nav-item"><a class="nav-link active" href="gcajast.php"><i class="fas fa-cash-register"></i> Registro de Ventas</a></li>
                    <li class="nav-item"><a class="nav-link" href="gproductostrabajador.php"><i class="fas fa-box"></i> Ver Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfil.php"><i class="fas fa-user"></i> Ver Perfil</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Grilla para mostrar las ventas -->
    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Reporte de Ventas</h5>

                        <!-- Ventas de hoy -->
                        <p class="card-text">
                            <strong>Ventas de hoy:</strong> $<?= number_format(obtenerVentas('hoy'), 2) ?>
                        </p>

                        <!-- Ventas de la semana -->
                        <p class="card-text">
                            <strong>Ventas de la semana:</strong> $<?= number_format(obtenerVentas('semana'), 2) ?>
                        </p>

                        <!-- Ventas del mes -->
                        <p class="card-text">
                            <strong>Ventas del mes:</strong> $<?= number_format(obtenerVentas('mes'), 2) ?>
                        </p>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Papelería</p>
    </footer>

    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión después de todo el procesamiento
$conn->close();
?>
