<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit();
}

include('conexion.php');

// Obtener los datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['productos'], $data['total'], $data['metodoPago'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud.']);
    exit();
}

// Iniciar transacción
$conn->begin_transaction();

try {
    // Registrar la venta
    $stmt = $conn->prepare("INSERT INTO ventas (total, metodo_pago) VALUES (?, ?)");
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta para registrar la venta: " . $conn->error);
    }
    $stmt->bind_param('ds', $data['total'], $data['metodoPago']);
    $stmt->execute();
    $venta_id = $stmt->insert_id; // Obtener el ID de la venta registrada
    $stmt->close();

    // Procesar cada producto
    foreach ($data['productos'] as $producto) {
        $id_producto = $producto['id'];
        $cantidad = $producto['cantidad'];
        $subtotal = $producto['subtotal'];

        // Verificar el stock
        $stmt = $conn->prepare("SELECT stock FROM productos WHERE id_producto = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta para verificar stock: " . $conn->error);
        }
        $stmt->bind_param('i', $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row || $row['stock'] < $cantidad) {
            throw new Exception("Stock insuficiente para el producto con ID: $id_producto.");
        }

        // Actualizar el stock
        $nuevoStock = $row['stock'] - $cantidad;
        $stmt = $conn->prepare("UPDATE productos SET stock = ? WHERE id_producto = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta para actualizar el stock: " . $conn->error);
        }
        $stmt->bind_param('ii', $nuevoStock, $id_producto);
        $stmt->execute();
        $stmt->close();

        // Registrar los detalles de la venta
        $stmt = $conn->prepare("INSERT INTO detalles_venta (id_venta, id_producto, cantidad, subtotal) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta para registrar detalles de la venta: " . $conn->error);
        }
        $stmt->bind_param('iiid', $venta_id, $id_producto, $cantidad, $subtotal);
        $stmt->execute();
        $stmt->close();
    }

    // Confirmar la transacción
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Venta registrada correctamente.', 'venta_id' => $venta_id]);

} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
