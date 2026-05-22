<?php
include __DIR__ . "/../includes/header.php";

$ruta_revistas = __DIR__ . '/../data/revistas.json';
$revistas = file_exists($ruta_revistas) ? json_decode(file_get_contents($ruta_revistas), true) : [];

$id_revista = $_GET['id'] ?? 1;
$revista_actual = null;
foreach ($revistas as $r) {
    if ($r['id'] == $id_revista) {
        $revista_actual = $r;
        break;
    }
}

if (!$revista_actual) {
    echo "<h1>Revista no encontrada</h1>";
    include __DIR__ . "/../includes/footer.php";
    exit;
}
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/revista.css">

<main class="magazine-main">
    <section class="magazine-section">
        <div class="container">

            <h2 class="section-title text-center" style="text-align: center; margin-bottom: 50px;">Revista micológica
                "Errotari" nº <?php echo htmlspecialchars($revista_actual['numero']); ?> - Año <?php echo htmlspecialchars($revista_actual['anio']); ?></h2>

            <div class="magazine-layout">

                <aside class="magazine-sidebar">
                    <div class="cover-image">
                        <img src="<?php echo htmlspecialchars($revista_actual['portada']); ?>"
                            alt="Portada de la Revista Errotari nº <?php echo htmlspecialchars($revista_actual['numero']); ?>" width="283" height="400">
                    </div>

                    <div class="purchase-info">
                        <span class="info-icon"></span>
                        <p>La revista se distribuye de forma gratuita entre los socios, se intercambia con publicaciones
                            de otras asociaciones o puede ser adquirida a un precio de <strong><?php echo htmlspecialchars($revista_actual['precio']); ?></strong> más gastos
                            de envío. Solicitudes a través de nuestro correo electrónico.</p>
                    </div>
                </aside>

                <article class="magazine-toc" id="contenido-pdf">

                    <div class="toc-header">
                        <h3 class="toc-title">Sumario</h3>
                        <button id="btn-pdf" class="btn-descarga" onclick="descargarPDF()">
                            📄 Descargar en PDF
                        </button>
                    </div>

                    <ol class="main-toc">
                        <?php foreach ($revista_actual['secciones'] as $seccion): ?>
                            <li>
                                <?php
                                // Detectar si el título de la sección tiene autor entre paréntesis
                                $titulo_sec = $seccion['titulo'];
                                $autor_sec = "";
                                if (preg_match('/^(.*?)\s*\((.*?)\)$/', $titulo_sec, $matches)) {
                                    $titulo_sec = $matches[1];
                                    $autor_sec = $matches[2];
                                }
                                ?>
                                <strong><?php echo htmlspecialchars($titulo_sec); ?></strong>
                                <?php if (!empty($autor_sec)): ?>
                                    <span class="author">(<?php echo htmlspecialchars($autor_sec); ?>)</span>
                                <?php endif; ?>

                                <?php if (!empty($seccion['articulos'])): ?>
                                    <ol class="sub-toc">
                                        <?php foreach ($seccion['articulos'] as $articulo): ?>
                                            <?php
                                            $tit_art = $articulo;
                                            $aut_art = "";
                                            if (preg_match('/^(.*?)\s*\((.*?)\)$/', $tit_art, $m)) {
                                                $tit_art = $m[1];
                                                $aut_art = $m[2];
                                            }
                                            ?>
                                            <li>
                                                <?php if (strpos($tit_art, '<em>') !== false): ?>
                                                    <?php echo $tit_art; // Permitimos cursivas ?>
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars($tit_art); ?>
                                                <?php endif; ?>
                                                <?php if (!empty($aut_art)): ?>
                                                    <span class="author">(<?php echo htmlspecialchars($aut_art); ?>)</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ol>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </article>

            </div>
        </div>
    </section>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function descargarPDF() {
        const elemento = document.getElementById('contenido-pdf');
        const boton = document.getElementById('btn-pdf');

        // Ocultamos el botón
        boton.style.display = 'none';

        // Configuramos cómo queremos el PDF
        const opciones = {
            margin: 15,
            filename: 'Sumario_Errotari_<?php echo htmlspecialchars($revista_actual['anio']); ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: {
                scale: 2,
                scrollY: 0
            },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak: { avoid: 'li' }
        };

        // Generamos y descargamos
        html2pdf().set(opciones).from(elemento).save().then(() => {
            // Volvemos a mostrar el botón
            boton.style.display = 'inline-flex';
        });
    }
</script>

<?php
include __DIR__ . "/../includes/footer.php";
?>