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
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
        }

        footer {
            margin-top: auto;
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

    <!-- Barra de navegación del administrador -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="administrador.php">Papelería</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="gestionproductos.php"><i class="fas fa-boxes"></i> Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestionproveedores.php"><i class="fas fa-truck"></i> Proveedores</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestiontrabajadores.php"><i class="fas fa-users"></i> Trabajadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestioncajas.php"><i class="fas fa-chart-line"></i> Ventas</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestionpromociones.php"><i class="fas fa-tags"></i> Promociones</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container my-5">
        <!-- Botón para añadir un producto -->
        <button class="btn btn-primary mb-4" onclick="mostrarFormulario()">
            <i class="fas fa-plus-circle"></i> Añadir Producto
        </button>

        <!-- Formulario para añadir productos -->
        <div id="formulario" class="hidden card p-4 mb-5 card-custom shadow">
            <h4 class="text-center mb-4">Agregar Nuevo Producto</h4>
            <div class="mb-3">
                <label for="producto" class="form-label">Nombre del Producto</label>
                <input type="text" id="producto" class="form-control" placeholder="Nombre del Producto" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" id="stock" class="form-control" placeholder="Stock" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" id="precio" class="form-control" placeholder="Precio" required>
            </div>
            <button type="button" class="btn btn-success" onclick="agregarProducto()">Agregar</button>
            <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
        </div>

        <!-- Formulario para editar productos -->
        <div id="formulario-editar" class="hidden card p-4 mb-5 card-custom shadow">
            <h4 class="text-center mb-4">Editar Producto</h4>
            <input type="hidden" id="id_producto_editar"> <!-- Campo oculto para el ID -->
            <div class="mb-3">
                <label for="producto_editar" class="form-label">Nombre del Producto</label>
                <input type="text" id="producto_editar" class="form-control" placeholder="Nombre del Producto" required>
            </div>
            <div class="mb-3">
                <label for="stock_editar" class="form-label">Stock</label>
                <input type="number" id="stock_editar" class="form-control" placeholder="Stock" required>
            </div>
            <div class="mb-3">
                <label for="precio_editar" class="form-label">Precio</label>
                <input type="number" id="precio_editar" class="form-control" placeholder="Precio" required>
            </div>
            <button type="button" class="btn btn-success" onclick="editarProducto()">Guardar Cambios</button>
            <button type="button" class="btn btn-secondary" onclick="ocultarFormularioEditar()">Cancelar</button>
        </div>

        <!-- Tabla de productos -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-productos">
                    <?php foreach ($productos as $producto): ?>
                        <tr data-id="<?php echo $producto['id_producto']; ?>">
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo $producto['stock']; ?></td>
                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning" onclick="mostrarFormularioEditar(<?php echo $producto['id_producto']; ?>, '<?php echo addslashes($producto['nombre']); ?>', <?php echo $producto['stock']; ?>, <?php echo $producto['precio']; ?>)">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-danger" onclick="eliminarProducto(<?php echo $producto['id_producto']; ?>)">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </td>
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
    
    <!-- JavaScript personalizado -->
    <script>
        // Función para mostrar el formulario de agregar producto
        function mostrarFormulario() {
            document.getElementById('formulario').classList.remove('hidden');
        }

        // Función para ocultar el formulario de agregar producto
        function ocultarFormulario() {
            document.getElementById('formulario').classList.add('hidden');
            document.getElementById('producto').value = '';
            document.getElementById('stock').value = '';
            document.getElementById('precio').value = '';
        }

        // Función para agregar un producto mediante AJAX
        function agregarProducto() {
            const producto = document.getElementById('producto').value;
            const stock = document.getElementById('stock').value;
            const precio = document.getElementById('precio').value;

            if (producto === '' || stock === '' || precio === '') {
                alert('Todos los campos son obligatorios');
                return;
            }

            const formData = new FormData();
            formData.append('nombre', producto);
            formData.append('stock', stock);
            formData.append('precio', precio);

            fetch('agregarproducto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const nuevaFila = `
                        <tr data-id="${data.id_producto}">
                            <td>${producto}</td>
                            <td>${stock}</td>
                            <td>$${parseFloat(precio).toFixed(2)}</td>
                            <td class="text-center">
                                <button class="btn btn-warning" onclick="mostrarFormularioEditar(${data.id_producto}, '${producto}', ${stock}, ${precio})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-danger" onclick="eliminarProducto(${data.id_producto})">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                    document.getElementById('tabla-productos').innerHTML += nuevaFila;
                    ocultarFormulario();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error al agregar producto:', error));
        }

        // Función para mostrar el formulario de edición
        function mostrarFormularioEditar(idProducto, nombre, stock, precio) {
            document.getElementById('id_producto_editar').value = idProducto;
            document.getElementById('producto_editar').value = nombre;
            document.getElementById('stock_editar').value = stock;
            document.getElementById('precio_editar').value = precio;
            document.getElementById('formulario-editar').classList.remove('hidden');
        }

        // Función para ocultar el formulario de edición
        function ocultarFormularioEditar() {
            document.getElementById('formulario-editar').classList.add('hidden');
            document.getElementById('producto_editar').value = '';
            document.getElementById('stock_editar').value = '';
            document.getElementById('precio_editar').value = '';
        }

        // Función para editar un producto mediante AJAX
        function editarProducto() {
            const idProducto = document.getElementById('id_producto_editar').value;
            const nombre = document.getElementById('producto_editar').value;
            const stock = document.getElementById('stock_editar').value;
            const precio = document.getElementById('precio_editar').value;

            if (nombre === '' || stock === '' || precio === '') {
                alert('Todos los campos son obligatorios');
                return;
            }

            const formData = new FormData();
            formData.append('id_producto', idProducto);
            formData.append('nombre', nombre);
            formData.append('stock', stock);
            formData.append('precio', precio);

            fetch('editarproducto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const filaProducto = document.querySelector(`tr[data-id="${idProducto}"]`);
                    filaProducto.cells[0].textContent = nombre;
                    filaProducto.cells[1].textContent = stock;
                    filaProducto.cells[2].textContent = '$' + parseFloat(precio).toFixed(2);
                    ocultarFormularioEditar();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error al editar el producto:', error));
        }

        function eliminarProducto(idProducto) {
            if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                fetch('eliminarproducto.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_producto=${idProducto}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || "Producto eliminado correctamente.");
                        const filaProducto = document.querySelector(`tr[data-id="${idProducto}"]`);
                        if (filaProducto) filaProducto.remove();
                    } else {
                        alert(data.message || "Error al eliminar el producto.");
                    }
                })
                .catch(error => console.error('Error:', error));
                }
            }
    </script>
</body>
</html>
