<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Incluir la conexión a la base de datos
include 'conexion.php';

// Consultar los proveedores desde la base de datos
$sql = "SELECT id_proveedor, nombre, contacto FROM proveedores";
$result = $conn->query($sql);

$proveedores = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $proveedores[] = $row;
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
    <title>Gestión de Proveedores</title>
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
        .hidden { display: none; }
        .card-custom { border-radius: 10px; }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <header class="bg-dark text-white text-center py-4">
        <h1>Gestión de Proveedores</h1>
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
                    <li class="nav-item"><a class="nav-link active" href="gestionproveedores.php"><i class="fas fa-truck"></i> Proveedores</a></li>
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
        <h2 class="text-center text-success mb-4"><i class="fas fa-truck"></i> Administración de Proveedores</h2>

        <!-- Botón para añadir nuevo proveedor -->
        <div class="text-end mb-4">
            <button class="btn btn-success" onclick="mostrarFormulario()"><i class="fas fa-plus-circle"></i> Agregar Proveedor</button>
        </div>

        <!-- Formulario de agregar proveedor -->
        <div id="formulario" class="hidden mb-4">
            <input type="text" id="nombre" placeholder="Nombre del Proveedor" required>
            <input type="email" id="contacto" placeholder="Contacto" required>
            <button class="btn btn-primary" onclick="agregarProveedor()">Agregar</button>
            <button class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
        </div>

        <!-- Tabla de proveedores -->
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-success">
                    <tr>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-proveedores">
                    <?php foreach ($proveedores as $proveedor): ?>
                        <tr data-id="<?php echo $proveedor['id_proveedor']; ?>">
                            <td><?php echo htmlspecialchars($proveedor['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['contacto']); ?></td>
                            <td>
                                <button class="btn btn-danger" onclick="eliminarProveedor(this)"><i class="fas fa-trash"></i> Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 Papelería. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para mostrar el formulario de agregar proveedor
        function mostrarFormulario() {
            document.getElementById('formulario').classList.remove('hidden');
        }

        // Función para ocultar el formulario
        function ocultarFormulario() {
            document.getElementById('formulario').classList.add('hidden');
            document.getElementById('nombre').value = '';
            document.getElementById('contacto').value = '';
        }

        // Función para agregar un proveedor
        function agregarProveedor() {
            const nombre = document.getElementById('nombre').value;
            const contacto = document.getElementById('contacto').value;

            if (nombre === '' || contacto === '') {
                alert('Todos los campos son obligatorios');
                return;
            }

            const formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('contacto', contacto);

            fetch('agregarproveedor.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tablaProveedores = document.getElementById('tabla-proveedores');
                    const nuevaFila = document.createElement('tr');
                    nuevaFila.innerHTML = `
                        <td>${nombre}</td>
                        <td>${contacto}</td>
                        <td><button class="btn btn-danger" onclick="eliminarProveedor(this)"><i class="fas fa-trash"></i> Eliminar</button></td>
                    `;
                    tablaProveedores.appendChild(nuevaFila);
                    ocultarFormulario();  // Ocultar el formulario
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error al agregar el proveedor:', error);
                alert('Hubo un problema al agregar el proveedor.');
            });
        }

        // Función para eliminar un proveedor
        function eliminarProveedor(button) {
            const fila = button.parentElement.parentElement;
            const idProveedor = fila.getAttribute('data-id');

            const formData = new FormData();
            formData.append('id_proveedor', idProveedor);

            fetch('eliminarproveedor.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fila.remove(); // Eliminar la fila de la tabla
                } else {
                    alert(data.message); // Mostrar el mensaje de error
                }
            })
            .catch(error => {
                console.error('Error al eliminar el proveedor:', error);
                alert('Hubo un problema al eliminar el proveedor.');
            });
        }
    </script>
</body>
</html>
