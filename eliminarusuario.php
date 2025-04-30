<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se ha enviado el ID del usuario
if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];

    // Verificar que el id_usuario es un número válido
    if (!is_numeric($id_usuario)) {
        echo json_encode(['success' => false, 'message' => 'ID de usuario inválido.']);
        exit();
    }

    // Preparar la consulta para verificar si el usuario existe
    $sql_check = "SELECT id_usuario FROM usuarios WHERE id_usuario = ?";
    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("i", $id_usuario);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows == 0) {
            // Si el usuario no existe, devolver mensaje de error
            echo json_encode(['success' => false, 'message' => 'El usuario no existe.']);
            $stmt_check->close();
            $conn->close();
            exit();
        }

        $stmt_check->close();
    }

    // Preparar la consulta para eliminar al usuario
    $sql_delete = "DELETE FROM usuarios WHERE id_usuario = ?";
    if ($stmt = $conn->prepare($sql_delete)) {
        // Enlazar el parámetro
        $stmt->bind_param("i", $id_usuario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la eliminación es exitosa, devolver mensaje de éxito
            echo json_encode(['success' => true]);
        } else {
            // Si hay algún problema al eliminar, devolver mensaje de error
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario.']);
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        // Si no se pudo preparar la consulta, devolver error
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta de eliminación.']);
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Si no se recibe el ID del usuario, devolver error
    echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado.']);
}
?>
