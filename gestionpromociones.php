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
                    <li class="nav-item"><a class="nav-link active" href="gestionpromociones.php"><i class="fas fa-tags"></i> Promociones</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Botón para mostrar el formulario -->
        <button class="btn btn-success mb-4" onclick="mostrarFormulario()">Añadir Promoción</button>

        <!-- Formulario oculto -->
        <div id="formulario" class="hidden mb-4">
            <!-- Selección de producto -->
            <div class="mb-3">
                <label for="producto" class="form-label">Seleccionar Producto</label>
                <select id="producto" class="form-select" required>
                    <option value="">Selecciona un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['id_producto'] ?>">
                            <?= $producto['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Campo de descuento -->
            <div class="mb-3">
                <input type="number" id="descuento" class="form-control" placeholder="Descuento (%)" required>
            </div>

            <!-- Fechas -->
            <div class="mb-3">
                <input type="date" id="fechaInicio" class="form-control" required>
            </div>
            <div class="mb-3">
                <input type="date" id="fechaFin" class="form-control" required>
            </div>

            <button class="btn btn-primary" onclick="agregarPromocion()">Agregar</button>
            <button class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
        </div>

        <!-- Tabla de promociones -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Descuento</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-promociones">
    <?php foreach ($promociones as $promocion): ?>
        <tr data-id="<?php echo $promocion['id_promocion']; ?>">
            <td><?php echo htmlspecialchars($promocion['producto_nombre']); ?></td>  <!-- Nombre del producto -->
            <td><?php echo htmlspecialchars($promocion['descuento']); ?>%</td>
            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($promocion['fecha_inicio']))); ?></td>
            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($promocion['fecha_fin']))); ?></td>
            <td>
                <button class="btn btn-danger" onclick="eliminarPromocion(this)">Eliminar</button>
            </td>
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

    <script>
        function mostrarFormulario() {
            document.getElementById('formulario').classList.remove('hidden');
        }

        function ocultarFormulario() {
            document.getElementById('formulario').classList.add('hidden');
            document.getElementById('producto').value = '';
            document.getElementById('descuento').value = '';
            document.getElementById('fechaInicio').value = '';
            document.getElementById('fechaFin').value = '';
        }

        function agregarPromocion() {
    const productoId = document.getElementById('producto').value;
    const descuento = document.getElementById('descuento').value;
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;

    // Validar que los campos no estén vacíos
    if (productoId === '' || descuento === '' || fechaInicio === '' || fechaFin === '') {
        alert('Todos los campos son obligatorios');
        return;
    }

    // Crear un objeto FormData para enviar los datos de manera adecuada
    const formData = new FormData();
    formData.append('producto_id', productoId);
    formData.append('descuento', descuento);
    formData.append('fechaInicio', fechaInicio);
    formData.append('fechaFin', fechaFin);

    // Enviar los datos al servidor mediante una solicitud POST
    fetch('agregarpromocion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Si la inserción es exitosa, agregar la promoción a la tabla
            const tablaPromociones = document.getElementById('tabla-promociones');
            const nuevaFila = document.createElement('tr');

            nuevaFila.innerHTML = `
                <td>${data.productoNombre}</td>
                <td>${data.descuento}%</td>
                <td>${data.fechaInicio}</td>
                <td>${data.fechaFin}</td>
                <td><button class="btn btn-danger" onclick="eliminarPromocion(this)">Eliminar</button></td>
            `;

            tablaPromociones.appendChild(nuevaFila);
            ocultarFormulario();  // Ocultar el formulario
        } else {
            alert(data.message);  // Mostrar el mensaje de error
        }
    })
    .catch(error => {
        console.error('Error al agregar la promoción:', error);
        alert('Hubo un problema al agregar la promoción.');
    });
}


        function eliminarPromocion(button) {
            const fila = button.parentElement.parentElement;
            const idPromocion = fila.getAttribute('data-id');

            // Enviar solicitud para eliminar la promoción
            fetch(`eliminarpromocion.php?id_promocion=${idPromocion}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Si la eliminación es exitosa, eliminar la fila de la tabla
                        fila.remove();
                    } else {
                        alert(data.message);  // Mostrar el mensaje de error
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar la promoción:', error);
                    alert('Hubo un problema al eliminar la promoción.');
                });
        }
    </script>
</body>
</html>
