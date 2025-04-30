<?php
include 'conexion.php';
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['productoId'])) {
    $productoId = $data['productoId'];
    
    // Verificar si el producto tiene un id_promocion asociado
    $sql = "SELECT p.descuento 
            FROM productos pr 
            JOIN promociones p ON pr.id_promocion = p.id_promocion
            WHERE pr.id_producto = ? AND p.activo = 1 AND CURDATE() BETWEEN p.fecha_inicio AND p.fecha_fin";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productoId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si el producto tiene una promoción activa, devolver el descuento
        $promocion = $result->fetch_assoc();
        echo json_encode(['promocion' => $promocion]);
    } else {
        // Si no tiene promoción activa
        echo json_encode(['promocion' => null]);
    }
} else {
    echo json_encode(['promocion' => null]);
}

$conn->close();
?>
