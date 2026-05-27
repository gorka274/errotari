<?php
include __DIR__ . "/../../includes/header.php";

// Credenciales dinámicas (Igual que en nombres comunes)
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    $user = "root";
    $password = "";
    $base = "bdmicol";
} else {
    $user = "myerrotari";
    $password = "R1hrkd0a";
    $base = "bdmicol";
}

$conexion = mysqli_connect("localhost", $user, $password, $base);

if (!$conexion) {
    echo "<div class='container'><p>Error: No se pudo conectar a MySQL.</p></div>";
    include __DIR__ . "/../includes/footer.php";
    exit;
}

// Obligamos a usar UTF-8 para evitar caracteres extraños
mysqli_set_charset($conexion, "utf8");

$tabla = "principal";

// 1. OBTENER LAS ESTADÍSTICAS (Optimizadas con COUNT)
$res_locales = mysqli_query($conexion, "SELECT COUNT(*) as total FROM $tabla WHERE local=1");
$numloc = mysqli_fetch_assoc($res_locales)['total'];

$res_enlaces = mysqli_query($conexion, "SELECT COUNT(*) as total FROM $tabla WHERE enlace=1");
$numenl = mysqli_fetch_assoc($res_enlaces)['total'];

$res_total = mysqli_query($conexion, "SELECT COUNT(*) as total FROM $tabla");
$numesp = mysqli_fetch_assoc($res_total)['total'];
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/especies.css">

<main class="especies-main" id="top">
    <section class="container">
        <h1 class="text-center">Índice de especies por nombre científico</h1>

        <div class="stats-box text-center">
            <p><strong><?php echo $numesp; ?></strong> especies en total | <strong><?php echo $numenl; ?></strong>
                fotografiadas | <strong><?php echo $numloc; ?></strong> especies locales (*)</p>
        </div>

        <div class="alfabeto-container text-center">
            <?php
            $letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'X'];
            foreach ($letras as $l) {
                echo "<a href='#letra-$l' class='letra-index'>$l</a>";
            }
            ?>
        </div>

        <div class="listado-cientifico">
            <?php
            $tabla_taxones = "taxones";

            for ($i = 0; $i <= 25; $i++) {
                $letra = chr($i + 65); // Genera letras de la A a la Z
            
                // Buscamos si hay géneros que empiecen por esta letra
                $resultado = mysqli_query($conexion, "SELECT nombre FROM $tabla_taxones WHERE clave='$letra' ORDER BY nombre");

                if (mysqli_num_rows($resultado) > 0) {
                    echo "<div class='bloque-letra'>";
                    echo "<h2 id='letra-$letra' class='titulo-letra'>$letra</h2>";

                    while ($row = mysqli_fetch_array($resultado)) {
                        $gen = $row[0];

                        // Buscamos las especies de este género
                        $lista = mysqli_query($conexion, "SELECT idesp, genero, especie, enlace, local FROM $tabla WHERE genero='$gen' ORDER BY especie");
                        $numlis = mysqli_num_rows($lista);

                        if ($numlis == 1) {
                            // Si solo hay una especie en este género, se imprime todo en una línea
                            $salida = mysqli_fetch_assoc($lista);
                            $local_mark = ($salida['local'] == 1) ? "<span class='asterisco'>*</span>" : "";

                            echo "<div class='item-unico'>";
                            if ($salida['enlace'] == 1) {
                                echo "<a href='" . BASE_URL . "/micologia/especies/ficha.php?" . $salida['idesp'] . "'><em>" . htmlspecialchars($salida['genero'] . " " . $salida['especie']) . "</em> $local_mark</a>";
                            } else {
                                echo "<em>" . htmlspecialchars($salida['genero'] . " " . $salida['especie']) . "</em> $local_mark";
                            }
                            echo "</div>";

                        } elseif ($numlis > 1) {
                            // Si hay varias especies, ponemos el género como título y las especies debajo en lista
                            echo "<div class='grupo-genero'>";
                            echo "<h3 class='nombre-genero'><em>" . htmlspecialchars($gen) . "</em></h3>";
                            echo "<ul class='lista-subespecies'>";

                            while ($salida = mysqli_fetch_assoc($lista)) {
                                $local_mark = ($salida['local'] == 1) ? "<span class='asterisco'>*</span>" : "";
                                echo "<li>";
                                if ($salida['enlace'] == 1) {
                                    echo "<a href='" . BASE_URL . "/micologia/especies/ficha.php?" . $salida['idesp'] . "'><em>" . htmlspecialchars($salida['especie']) . "</em> $local_mark</a>";
                                } else {
                                    echo "<em>" . htmlspecialchars($salida['especie']) . "</em> $local_mark";
                                }
                                echo "</li>";
                            }

                            echo "</ul>";
                            echo "</div>"; // Fin grupo-genero
                        }
                    }
                    echo "<a href='#top' class='btn-arriba'>↑ Volver arriba</a>";
                    echo "</div>"; // Fin bloque-letra
                }
            }
            ?>
        </div>
    </section>
</main>

<?php
mysqli_close($conexion);
include __DIR__ . "/../../includes/footer.php";
?>