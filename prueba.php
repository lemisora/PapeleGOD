<?php
include 'conexion.php';

$userId = 8; // Cambiar por el ID del usuario que deseas probar
$newPassword = '12345678';
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

$sql = "UPDATE usuarios SET password_hash = ? WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $hashedPassword, $userId);

if ($stmt->execute()) {
    echo "Contraseña actualizada correctamente.";
} else {
    echo "Error al actualizar: " . $stmt->error;
}

$conn->close();
?>
