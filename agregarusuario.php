<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se recibieron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados desde el formulario
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $rol = isset($_POST['rol']) ? (int)$_POST['rol'] : 0;  // Convertir el rol a un número entero
    $password_hash = isset($_POST['password_hash']) ? $_POST['password_hash'] : '';

    // Validar que todos los campos sean válidos
    if (empty($nombre) || empty($email) || empty($usuario) || empty($password_hash) || $rol <= 0) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit();
    }

    // Validar formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
        exit();
    }

    // Validar si el nombre de usuario ya existe en la base de datos
    $sql_check_usuario = "SELECT id_usuario FROM usuarios WHERE usuario = ?";
    $stmt_check_usuario = $conn->prepare($sql_check_usuario);
    $stmt_check_usuario->bind_param("s", $usuario);
    $stmt_check_usuario->execute();
    $stmt_check_usuario->store_result();
    if ($stmt_check_usuario->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya está en uso.']);
        exit();
    }

    // Encriptar la contraseña
    $password_hash = password_hash($password_hash, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario en la base de datos
    $sql_insert = "INSERT INTO usuarios (nombre, email, usuario, id_rol, password_hash) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sssds", $nombre, $email, $usuario, $rol, $password_hash);

    if ($stmt->execute()) {
        // Devolver respuesta exitosa
        echo json_encode(['success' => true, 'message' => 'Usuario agregado correctamente.']);
    } else {
        // Devolver error si la inserción falla
        echo json_encode(['success' => false, 'message' => 'Hubo un problema al agregar el usuario.']);
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    // Si no es una solicitud POST
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
