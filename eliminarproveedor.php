<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se ha enviado el ID del proveedor
if (isset($_POST['id_proveedor'])) {
    $id_proveedor = $_POST['id_proveedor'];

    // Verificar si el proveedor existe
    $sql_check = "SELECT id_proveedor FROM proveedores WHERE id_proveedor = ?";
    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("i", $id_proveedor);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows == 0) {
            echo json_encode(['success' => false, 'message' => 'El proveedor no existe.']);
            $stmt_check->close();
            $conn->close();
            exit();
        }

        $stmt_check->close();
    }

    // Preparar la consulta para eliminar el proveedor
    $sql = "DELETE FROM proveedores WHERE id_proveedor = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Enlazar el parámetro
        $stmt->bind_param("i", $id_proveedor);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el proveedor.']);
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta de eliminación.']);
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'ID de proveedor no proporcionado.']);
}
?>
