<?php
// Inicia la sesión si aún no está iniciada
session_start();

// Elimina la información de sesión
session_destroy();

// Redirige a la página de inicio
header('location: index.php');
?>