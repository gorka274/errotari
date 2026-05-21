<?php
session_start();

// 1. Guardián de seguridad
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Saber en qué sección estamos
$modulo_actual = $_GET['mod'] ?? 'inicio';

// Validar módulos permitidos para evitar inclusiones indeseadas
$modulos_permitidos = ['inicio', 'prog-anual', 'prog-fiestas', 'prog-blog', 'revista-crear', 'revista-modificar'];
if (!in_array($modulo_actual, $modulos_permitidos)) {
    $modulo_actual = 'inicio';
}

$usuario_nombre = $_SESSION['usuario'] ?? 'Admin';

// Variables globales para mensajes de los módulos
$mensaje_exito = "";
$mensaje_error = "";

// Incluir cabecera HTML (CSS y head)
require_once __DIR__ . '/includes/header.php';
// Incluir menú lateral
require_once __DIR__ . '/includes/sidebar.php';
?>

<main class="main-content">
    <div class="top-bar">
        <h2>Panel > <?php echo ucfirst(str_replace('-', ' ', $modulo_actual)); ?></h2>
        <div>Conectado como: <strong style="color:var(--primary-color);"><?php echo htmlspecialchars($usuario_nombre); ?></strong></div>
    </div>

    <div class="content-body">
        <div class="work-card">
            <?php
            // Cargar el módulo solicitado
            $ruta_modulo = __DIR__ . '/modulos/' . $modulo_actual . '.php';
            if (file_exists($ruta_modulo)) {
                require $ruta_modulo;
            } else {
                echo "<h1>Sección en desarrollo</h1><p>Pronto se vinculará la lógica para esta opción del panel.</p>";
            }
            ?>
        </div>
    </div>
</main>

<?php
// Incluir cierre HTML
require_once __DIR__ . '/includes/footer.php';
?>