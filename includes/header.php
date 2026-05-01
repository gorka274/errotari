<?php
// Detectamos si estamos en local (tu ordenador) o en producción (Internet)
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    define('BASE_URL', '/errotari');
} else {
    define('BASE_URL', '');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <title>Sociedad Micológica Errotari</title>
</head>

<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>/index.php">
                    <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="Logo de Errotari">
                </a>
            </div>

            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>/index.php" class="active">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/blog.php">Blog</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/micologia.php">Micología</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/revista.php">Revista</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/contacto.php">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>