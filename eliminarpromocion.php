<?php
include 'conexion.php';

// Verificar si se ha recibido el ID de la promoción
if (isset($_GET['id_promocion'])) {
    $idPromocion = $_GET['id_promocion'];

    // Eliminar la promoción de la base de datos
    $sql = "UPDATE promociones SET activo = 0 WHERE id_promocion = $idPromocion"; // Marcamos como inactiva

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar la promoción: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de promoción no especificado']);
}

$conn->close();
?>
