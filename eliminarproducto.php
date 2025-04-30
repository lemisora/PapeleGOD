<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'conexion.php';

    if (isset($_POST['id_producto'])) {
        $id_producto = intval($_POST['id_producto']);
        
        $sql = "DELETE FROM productos WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_producto);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Producto eliminado.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID del producto no proporcionado.']);
    }
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
