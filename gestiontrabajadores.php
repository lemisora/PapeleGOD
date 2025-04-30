<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Consulta SQL para obtener todos los roles
$sql_roles = "SELECT id_rol, nombre FROM roles";
$result_roles = $conn->query($sql_roles);

// Almacenar los roles
$roles = [];
if ($result_roles->num_rows > 0) {
    while ($row = $result_roles->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Consulta SQL para obtener todos los usuarios junto con su rol
$sql = "SELECT u.id_usuario, u.nombre, u.email, u.usuario, r.nombre AS rol
        FROM usuarios u
        JOIN roles r ON u.id_rol = r.id_rol";  // Unimos con la tabla roles para obtener el nombre del rol

$result = $conn->query($sql);

// Almacenar los usuarios
$usuarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
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
    <title>Gestión de Usuarios - Papelería</title>
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
        <h1>Gestión de Trabajadores</h1>
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
                    <li class="nav-item"><a class="nav-link active" href="gestiontrabajadores.php"><i class="fas fa-users"></i> Trabajadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestioncajas.php"><i class="fas fa-chart-line"></i> Ventas</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestionpromociones.php"><i class="fas fa-tags"></i> Promociones</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <!-- Botón para mostrar el formulario -->
        <button class="btn btn-success" onclick="mostrarFormulario()">Añadir Usuario</button>

        <!-- Formulario de añadir usuario -->
        <div id="formulario" class="hidden mt-4">
            <div class="mb-3">
                <input type="text" id="nombre" class="form-control" placeholder="Nombre Completo">
            </div>
            <div class="mb-3">
                <input type="email" id="email" class="form-control" placeholder="Correo Electrónico">
            </div>
            <div class="mb-3">
                <input type="text" id="usuario" class="form-control" placeholder="Nombre de Usuario">
            </div>
            <div class="mb-3">
                <select id="rol" class="form-select">
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?php echo $rol['id_rol']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <input type="password" id="password_hash" class="form-control" placeholder="Contraseña">
            </div>
            <button class="btn btn-primary" onclick="agregarUsuario()">Agregar</button>
            <button class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
        </div>

        <br><br>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-usuarios">
                <?php foreach ($usuarios as $usuario): ?>
                    <tr data-id="<?php echo $usuario['id_usuario']; ?>">
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                        <td><button class="btn btn-danger" onclick="eliminarUsuario(this)">Eliminar</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Papelería</p>
    </footer>

    <!-- Incluir los scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para mostrar el formulario de agregar usuario
        function mostrarFormulario() {
            document.getElementById('formulario').classList.remove('hidden');
        }

        // Función para ocultar el formulario
        function ocultarFormulario() {
            document.getElementById('formulario').classList.add('hidden');
            document.getElementById('nombre').value = '';
            document.getElementById('email').value = '';
            document.getElementById('usuario').value = '';
            document.getElementById('password_hash').value = '';
        }

        // Función para agregar un usuario
        function agregarUsuario() {
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const usuario = document.getElementById('usuario').value;
            const rol = document.getElementById('rol').value;
            const password_hash = document.getElementById('password_hash').value;

            // Validar que los campos no estén vacíos
            if (nombre === '' || email === '' || usuario === '' || password_hash === '') {
                alert('Todos los campos son obligatorios');
                return;
            }

            // Crear un objeto FormData para enviar los datos de manera adecuada
            const formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('email', email);
            formData.append('usuario', usuario);
            formData.append('rol', rol);  // Enviar el rol como ID
            formData.append('password_hash', password_hash);

            // Enviar los datos al servidor mediante una solicitud POST
            fetch('agregarusuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Si la inserción es exitosa, agregar el usuario a la tabla
                    const tablaUsuarios = document.getElementById('tabla-usuarios');
                    const nuevaFila = document.createElement('tr');

                    nuevaFila.innerHTML = `
                        <td>${nombre}</td>
                        <td>${email}</td>
                        <td>${usuario}</td>
                        <td>${rol}</td>
                        <td><button class="btn btn-danger" onclick="eliminarUsuario(this)">Eliminar</button></td>
                    `;

                    tablaUsuarios.appendChild(nuevaFila);
                    ocultarFormulario();  // Ocultar el formulario
                } else {
                    alert(data.message);  // Mostrar el mensaje de error
                }
            })
            .catch(error => {
                console.error('Error al agregar el usuario:', error);
                alert('Hubo un problema al agregar el usuario.');
            });
        }

        // Función para eliminar un usuario
        function eliminarUsuario(button) {
            const fila = button.parentElement.parentElement;
            const idUsuario = fila.getAttribute('data-id');

            // Enviar solicitud para eliminar el usuario
            fetch(`eliminarusuario.php?id_usuario=${idUsuario}`)
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
                    console.error('Error al eliminar el usuario:', error);
                    alert('Hubo un problema al eliminar el usuario.');
                });
        }
    </script>
</body>
</html>
