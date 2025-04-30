<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se han enviado los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $stock = $_POST['stock'];
    $precio = $_POST['precio'];

    // Validar que todos los campos tengan valores válidos
    if (empty($nombre) || empty($stock) || empty($precio)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit();
    }

    // Validar si el stock y el precio son numéricos
    if (!is_numeric($stock) || !is_numeric($precio)) {
        echo json_encode(['success' => false, 'message' => 'El stock y el precio deben ser números.']);
        exit();
    }

    // Preparar la consulta para insertar el producto
    $sql = "INSERT INTO productos (nombre, stock, precio) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Enlazar los parámetros
        $stmt->bind_param("sii", $nombre, $stock, $precio);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Producto agregado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar el producto.']);
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
