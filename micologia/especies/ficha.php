<?php
include __DIR__ . "/../../includes/header.php";

// 1. OBTENER Y SANITIZAR EL ID (¡Muy importante para la seguridad!)
// Tomamos lo que hay después del "?" en la URL y nos aseguramos de que sea un número.
$ID = intval($_SERVER['QUERY_STRING']);

if ($ID === 0) {
    echo "<div class='container'><p>Seta no encontrada o ID inválido.</p></div>";
    include __DIR__ . "/../includes/footer.php";
    exit;
}

// 2. CONEXIÓN A LA BASE DE DATOS (Dinámica)
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
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

// 3. ACTUALIZAR ESTADÍSTICAS (Visitas)
mysqli_query($conexion, "UPDATE principal SET estad=estad+1 WHERE idesp=$ID");

// 4. DATOS PRINCIPALES DE LA ESPECIE
$res_ppal = mysqli_query($conexion, "SELECT genero, especie, autor, comer, observaciones, ecologia FROM principal WHERE idesp=$ID");
$datos_ppal = mysqli_fetch_assoc($res_ppal);

$especie_nombre = "<em>" . htmlspecialchars($datos_ppal['genero'] . " " . $datos_ppal['especie']) . "</em>";
$autor = htmlspecialchars($datos_ppal['autor']);

// Mapa de comestibilidad (Más limpio que usar 11 "if" seguidos)
$mapa_comestibilidad = [
    1 => "Mortal",
    2 => "Muy tóxica",
    3 => "Tóxica",
    4 => "Sospechosa o desconocida",
    5 => "Mediocre o sin valor",
    6 => "Comestible (Tóxica en crudo)",
    7 => "Buena (Tóxica en crudo)",
    8 => "Excelente (Tóxica en crudo)",
    9 => "Comestible",
    10 => "Buena",
    11 => "Excelente"
];
$comestibilidad = $mapa_comestibilidad[$datos_ppal['comer']] ?? "Desconocida";

// 5. PUBLICACIONES
$res_pub = mysqli_query($conexion, "SELECT pub, pubmas, publoc, pubvol, pubpart, pubpage, pubyear, pubyearof FROM principal WHERE idesp=$ID");
$pub = mysqli_fetch_assoc($res_pub);
$publicacion = "";
if ($pub['pub'])
    $publicacion .= "<em>" . htmlspecialchars($pub['pub']) . "</em>";
if ($pub['pubmas'])
    $publicacion .= ", " . htmlspecialchars($pub['pubmas']);
if ($pub['publoc'])
    $publicacion .= ", (" . htmlspecialchars($pub['publoc']) . ")";
if ($pub['pubvol'])
    $publicacion .= ", <strong>" . htmlspecialchars($pub['pubvol']) . "</strong>";
if ($pub['pubpart'])
    $publicacion .= "(" . htmlspecialchars($pub['pubpart']) . ")";
if ($pub['pubpage'])
    $publicacion .= " :" . htmlspecialchars($pub['pubpage']);
if ($pub['pubyear'])
    $publicacion .= " (" . htmlspecialchars($pub['pubyear']) . ")";
if ($pub['pubyearof'])
    $publicacion .= " [" . htmlspecialchars($pub['pubyearof']) . "]";

// 6. NOMBRES COMUNES
$res_nom = mysqli_query($conexion, "SELECT nombresc, nombrese FROM principal WHERE idesp=$ID");
$nombres = mysqli_fetch_assoc($res_nom);
$nombres_comunes = ($nombres['nombresc'] || $nombres['nombrese']) ? htmlspecialchars($nombres['nombresc'] . ", " . $nombres['nombrese']) : null;

// 7. FOTOGRAFÍAS
$res_fotos = mysqli_query($conexion, "SELECT fecha, lugar, habitat, idfoto FROM fotografias WHERE idesp=$ID ORDER by idfoto");
$fotos = [];
while ($row = mysqli_fetch_assoc($res_fotos)) {
    $fotos[] = $row;
}

// 8. SINÓNIMOS
$res_sin = mysqli_query($conexion, "SELECT especie, autor FROM sinonimos WHERE idesp=$ID");
$sinonimos = [];
while ($row = mysqli_fetch_assoc($res_sin)) {
    $sinonimos[] = $row;
}

// 9. CRONOLOGÍA (Grafo)
$res_cron = mysqli_query($conexion, "SELECT 1 FROM cronologia WHERE idesp=$ID");
$tiene_grafo = (mysqli_num_rows($res_cron) > 0);

// 10. BIBLIOGRAFÍA
$res_bib = mysqli_query($conexion, "SELECT * FROM biblio WHERE idesp=$ID");
$libros = [];
if (mysqli_num_rows($res_bib) > 0) {
    $catalogo = mysqli_fetch_assoc($res_bib);
    $num_campos = mysqli_num_fields($res_bib);

    for ($a = 0; $a < $num_campos; $a++) {
        $nomf = "l" . $a; // Busca columnas l0, l1, l2...
        if (isset($catalogo[$nomf]) && $catalogo[$nomf]) {
            $res_libro = mysqli_query($conexion, "SELECT id, autor, titulo FROM libros WHERE id='$a'");
            if ($row_libro = mysqli_fetch_assoc($res_libro)) {
                $ref = $row_libro['id'];
                if ($catalogo[$nomf] != "+") {
                    $ref .= " - " . $catalogo[$nomf]; // Añade la página si no es un "+"
                }
                $libros[] = [
                    'autor' => $row_libro['autor'],
                    'titulo' => $row_libro['titulo'],
                    'ref' => $ref
                ];
            }
        }
    }
}

mysqli_close($conexion);
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ficha.css">

<main class="ficha-main">
    <div class="container">

        <div class="ficha-header text-center">
            <h1><?php echo $especie_nombre; ?> <span class="autor"><?php echo $autor; ?></span></h1>
            <?php if ($publicacion): ?>
                <p class="publicacion"><?php echo $publicacion; ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($fotos)): ?>
            <div class="ficha-galeria">
                <div class="foto-principal-container">
                    <img id="foto-principal" src="fotos/<?php echo htmlspecialchars($fotos[0]['idfoto']); ?>.jpg"
                        alt="Foto principal de la seta">

                    <div class="foto-info">
                        <span id="foto-fecha">📅 <?php echo htmlspecialchars($fotos[0]['fecha']); ?></span>
                        <span id="foto-lugar">📍 <?php echo htmlspecialchars($fotos[0]['lugar']); ?></span>
                        <span id="foto-habitat">🌲 <?php echo htmlspecialchars($fotos[0]['habitat']); ?></span>
                    </div>
                </div>

                <?php if (count($fotos) > 1): ?>
                    <div class="miniaturas-container">
                        <?php foreach ($fotos as $index => $foto): ?>
                            <img class="miniatura <?php echo $index === 0 ? 'activa' : ''; ?>"
                                src="fotos/<?php echo htmlspecialchars($foto['idfoto']); ?>.jpg" alt="Miniatura"
                                data-foto="fotos/<?php echo htmlspecialchars($foto['idfoto']); ?>.jpg"
                                data-fecha="<?php echo htmlspecialchars($foto['fecha']); ?>"
                                data-lugar="<?php echo htmlspecialchars($foto['lugar']); ?>"
                                data-habitat="<?php echo htmlspecialchars($foto['habitat']); ?>" onclick="cambiarFoto(this)">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="ficha-datos">
            <?php if ($nombres_comunes): ?>
                <div class="dato-bloque">
                    <h3>Nombres comunes:</h3>
                    <p><?php echo $nombres_comunes; ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($sinonimos)): ?>
                <div class="dato-bloque">
                    <h3>Sinónimos:</h3>
                    <ul>
                        <?php foreach ($sinonimos as $sin): ?>
                            <li><em><?php echo htmlspecialchars($sin['especie']); ?></em> <span
                                    class="autor"><?php echo htmlspecialchars($sin['autor']); ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="dato-bloque destaque">
                <h3>Comestibilidad:</h3>
                <p><strong><?php echo $comestibilidad; ?></strong></p>
            </div>

            <?php if ($datos_ppal['observaciones']): ?>
                <div class="dato-bloque">
                    <h3>Observaciones:</h3>
                    <p><?php echo nl2br(str_replace('especie.php?', 'ficha.php?', $datos_ppal['observaciones'])); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($datos_ppal['ecologia']): ?>
                <div class="dato-bloque">
                    <h3>Ecología:</h3>
                    <p><?php echo nl2br($datos_ppal['ecologia']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($libros)): ?>
                <div class="dato-bloque">
                    <h3>Bibliografía:</h3>
                    <ul class="lista-biblio">
                        <?php foreach ($libros as $lib): ?>
                            <li><strong><?php echo htmlspecialchars($lib['titulo']); ?></strong>,
                                <?php echo htmlspecialchars($lib['autor']); ?> (Ref:
                                <?php echo htmlspecialchars($lib['ref']); ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($tiene_grafo): ?>
            <div class="ficha-grafo text-center">
                <h3>Cronología de aparición</h3>
                <img class="img-grafo" src="grafo.php?<?php echo $ID; ?>" alt="Gráfico de aparición">

                <div class="leyenda-grafo">
                    <span class="leyenda-item"><span class="color-box c-rara"></span> Rara o esporádica</span>
                    <span class="leyenda-item"><span class="color-box c-comun"></span> Común</span>
                    <span class="leyenda-item"><span class="color-box c-bastante"></span> Bastante común</span>
                    <span class="leyenda-item"><span class="color-box c-abundante"></span> Abundante</span>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center" style="margin-top: 40px;">
            <a href="javascript:history.back()" class="btn-volver">← Volver al listado</a>
        </div>

    </div>
</main>

<script>
    function cambiarFoto(elemento) {
        // Cambiamos la imagen principal
        document.getElementById('foto-principal').src = elemento.getAttribute('data-foto');

        // Cambiamos los textos de información
        document.getElementById('foto-fecha').innerText = "📅 " + elemento.getAttribute('data-fecha');
        document.getElementById('foto-lugar').innerText = "📍 " + elemento.getAttribute('data-lugar');
        document.getElementById('foto-habitat').innerText = "🌲 " + elemento.getAttribute('data-habitat');

        // Quitamos la clase 'activa' a todas las miniaturas y se la ponemos a la clicada
        let miniaturas = document.querySelectorAll('.miniatura');
        miniaturas.forEach(m => m.classList.remove('activa'));
        elemento.classList.add('activa');
    }
</script>

<?php include __DIR__ . "/../../includes/footer.php"; ?>