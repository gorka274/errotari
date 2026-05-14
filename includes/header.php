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

                    <li class="has-submenu">
                        <a href="<?php echo BASE_URL; ?>/blog/principal.php">Blog</a>
                        <ul class="submenu">
                            <li><a href="<?php echo BASE_URL; ?>/blog/programaAnual.php">Programa Anual</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/blog/programaFiestas.php">Programa Fiestas</a></li>

                            <li class="has-submenu">
                                <a href="#">Concurso Fotográfico</a>
                                <ul class="submenu nested-submenu">
                                    <li><a href="<?php echo BASE_URL; ?>/blog/concursoFotografico/2003.php">2003</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="javascript:void(0);">Micología</a>
                        <ul class="submenu">
                            <li class="has-submenu">
                                <a href="javascript:void(0);">Iniciación</a>
                                <ul class="submenu nested-submenu">
                                    <li><a href="<?php echo BASE_URL; ?>/micologia/setasHongos.php">Setas y hongos</a>
                                    </li>
                                    <li><a href="<?php echo BASE_URL; ?>/micologia/caracteresOrganolepticos.php">Caracteres
                                            Organolépticos</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/micologia/principalesGeneros.php">Principales
                                            Géneros</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo BASE_URL; ?>/micologia/galeriaFotografica.php">Galería
                                    Fotográfica</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/micologia/especies.php">Especies</a></li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="javascript:void(0);">Revista</a>
                        <ul class="submenu">
                            <li><a href="<?php echo BASE_URL; ?>/revista/revista1.php">Revista nº 1</a></li>
                        </ul>
                    </li>

                    <li><a href="<?php echo BASE_URL; ?>/contacto.php">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>