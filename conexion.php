<?php
// Definir las credenciales de conexión
$host = 'localhost';  // O el nombre del servidor de tu base de datos
$db = 'papeleria';    // El nombre de tu base de datos
$user = 'root';       // Tu usuario de MySQL
$pass = 'lofi7677j';           // Tu contraseña de MySQL (si la tienes, de lo contrario déjalo vacío)

// Crear la conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar si hay errores en la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
