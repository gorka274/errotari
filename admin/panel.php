<?php
session_start();

// Si NO existe la sesión de admin o no es verdadera, lo expulsamos inmediatamente al login
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit;
}

// A partir de aquí, el código de tu panel de administración seguro...
?>
<h1>¡Bienvenido al panel, jefe!</h1>
<p>Aquí irá el formulario para crear las nuevas revistas.</p>
<a href="logout.php">Cerrar sesión</a>