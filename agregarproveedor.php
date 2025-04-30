<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se han enviado los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $contacto = $_POST['contacto'];

    // Validar que todos los campos tengan valores válidos
    if (empty($nombre) || empty($contacto)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit();
    }

    // Preparar la consulta para insertar el proveedor
    $sql = "INSERT INTO proveedores (nombre, contacto) VALUES (?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Enlazar los parámetros
        $stmt->bind_param("ss", $nombre, $contacto);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Proveedor agregado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar el proveedor.']);
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    }

    // Cerrar la conexión
    $conn->close();
}
?>
