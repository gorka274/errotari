<?php
include __DIR__ . "/../../includes/header.php";

// Detectamos si estamos en local o en el servidor real para usar la BD correcta
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    // Configuración para tu XAMPP local
    $user = "root";
    $password = "";
    $base = "bdmicol";
} else {
    // Configuración para el servidor de Internet (Producción)
    $user = "myerrotari";
    $password = "R1hrkd0a";
    $base = "bdmicol";
}

$tabla = "principal";

// Intentamos la conexión
$conexion = mysqli_connect("localhost", $user, $password, $base);

if (!$conexion) {
    echo "<div class='container'><p>Error: No se pudo conectar a MySQL.</p></div>";
    include __DIR__ . "/../../includes/footer.php";
    exit;
}

// SOLUCIÓN AL PROBLEMA DE LOS CARACTERES ()
mysqli_set_charset($conexion, "utf8");

// Ejecutamos la consulta tal cual la tenías
$query = "SELECT genero, especie, nombresc, nombrese, idesp, enlace FROM $tabla WHERE (nombresc <> '' OR nombrese <> '') ORDER BY nombresc";
$resultado = mysqli_query($conexion, $query);
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/especies.css">

<main class="especies-main">
    <section class="container">

        <h1 class="text-center" style="margin-bottom: 40px;">Índice de especies por nombres comunes</h1>

        <div class="table-responsive">
            <table class="tabla-especies">
                <thead>
                    <tr>
                        <th>Castellano</th>
                        <th>Euskera</th>
                        <th>Nombre Científico</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Usamos mysqli_fetch_assoc para leer por nombre de columna en vez de números. Es mucho más limpio.
                    while ($salida = mysqli_fetch_assoc($resultado)):

                        // Preparamos el nombre científico en cursiva como dicta la norma micológica
                        $nombre_cientifico = "<em>" . htmlspecialchars($salida['genero'] . " " . $salida['especie']) . "</em>";
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($salida['nombresc']); ?></td>
                            <td><?php echo htmlspecialchars($salida['nombrese']); ?></td>
                            <td>
                                <?php if ($salida['enlace'] == 1): ?>
                                    <a
                                        href="<?php echo BASE_URL; ?>/micologia/especies/ficha.php?<?php echo $salida['idesp']; ?>">
                                        <?php echo $nombre_cientifico; ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo $nombre_cientifico; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </section>
</main>

<?php
mysqli_close($conexion);
include __DIR__ . "/../../includes/footer.php";
?>