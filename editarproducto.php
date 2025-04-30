<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se enviaron los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'], $_POST['nombre'], $_POST['stock'], $_POST['precio'])) {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $stock = $_POST['stock'];
    $precio = $_POST['precio'];

    // Actualizar el producto en la base de datos
    $sql = "UPDATE productos SET nombre = ?, stock = ?, precio = ? WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdii', $nombre, $stock, $precio, $id_producto);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto']);
    }

    $stmt->close();
}
?>
