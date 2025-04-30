<?php
include 'conexion.php';

$response = ['success' => false, 'message' => ''];

// Verificar si los datos fueron enviados
if (isset($_POST['producto_id'], $_POST['descuento'], $_POST['fechaInicio'], $_POST['fechaFin'])) {
    $productoId = intval($_POST['producto_id']);
    $descuento = floatval($_POST['descuento']);
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    // Validar el rango del descuento
    if ($descuento <= 0 || $descuento > 100) {
        $response['message'] = 'El descuento debe estar entre 1 y 100.';
        echo json_encode($response);
        exit;
    }

    // Validar las fechas
    if (strtotime($fechaInicio) >= strtotime($fechaFin)) {
        $response['message'] = 'La fecha de inicio debe ser anterior a la fecha de fin.';
        echo json_encode($response);
        exit;
    }

    // Verificar que el producto existe
    $sqlProducto = "SELECT nombre FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sqlProducto);
    $stmt->bind_param("i", $productoId);
    $stmt->execute();
    $resultProducto = $stmt->get_result();

    if ($resultProducto->num_rows === 0) {
        $response['message'] = 'El producto seleccionado no existe.';
        echo json_encode($response);
        exit;
    }

    $producto = $resultProducto->fetch_assoc();

    // Insertar la promoción
    $sqlInsert = "INSERT INTO promociones (producto_id, descuento, fecha_inicio, fecha_fin, activo) VALUES (?, ?, ?, ?, 1)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("idss", $productoId, $descuento, $fechaInicio, $fechaFin);

    if ($stmtInsert->execute()) {
        // Obtener el ID de la nueva promoción
        $idPromocion = $stmtInsert->insert_id;  // ID de la promoción recién insertada

        // Actualizar el producto con el id_promocion
        $sqlUpdateProducto = "UPDATE productos SET id_promocion = ? WHERE id_producto = ?";
        $stmtUpdateProducto = $conn->prepare($sqlUpdateProducto);
        $stmtUpdateProducto->bind_param("ii", $idPromocion, $productoId);
        $stmtUpdateProducto->execute();

        // Enviar la respuesta
        $response['success'] = true;
        $response['productoNombre'] = $producto['nombre'];
        $response['descuento'] = $descuento;
        $response['fechaInicio'] = date('d/m/Y', strtotime($fechaInicio));
        $response['fechaFin'] = date('d/m/Y', strtotime($fechaFin));
    } else {
        $response['message'] = 'Error al agregar la promoción. Intenta nuevamente.';
    }
} else {
    $response['message'] = 'Datos incompletos.';
}

echo json_encode($response);
$conn->close();
?>
