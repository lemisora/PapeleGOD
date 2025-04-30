<?php
// Iniciar la sesión
session_start();

// Regenerar el ID de sesión para prevenir secuestro de sesión
session_regenerate_id(true);

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Eliminar la cookie de sesión en el navegador (opcional, en caso de que sea necesario)
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/'); // Expirar la cookie
}

// Desactivar la caché del navegador para evitar que el usuario vuelva atrás después de hacer logout
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache'); // Para HTTP/1.0
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Expirar en una fecha pasada

// Redirigir al usuario a la página de login
header('Location: login.php');
exit();
?>
