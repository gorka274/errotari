<?php
include __DIR__ . "/../includes/header.php";
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/programas.css">

<?php
// Ruta relativa hacia el archivo JSON (ajusta la ruta si tu carpeta data está en otro sitio)
$ruta_json_anual = __DIR__ . '/../data/programa_anual.json';
$programa = [];

// Comprobamos si el archivo existe y lo leemos
if (file_exists($ruta_json_anual)) {
    $json_data = file_get_contents($ruta_json_anual);
    $programa = json_decode($json_data, true);
}
?>

<div class="cuerpo">
    <div class="cuerpo1">
        <div class="titulo">
            <?php echo htmlspecialchars($programa['titulo'] ?? 'Programa Anual'); ?>
        </div>
        <p></p>

        <?php if (!empty($programa['contenido_bloques'])): ?>
            <?php foreach ($programa['contenido_bloques'] as $bloque): ?>

                <?php if ($bloque['tipo'] === 'seccion'): ?>
                    <span class="th2"><?php echo htmlspecialchars($bloque['titulo_seccion']); ?></span>
                <?php endif; ?>

                <p><?php echo nl2br(htmlspecialchars($bloque['texto'])); ?></p>

            <?php endforeach; ?>
        <?php else: ?>
            <p>La información del programa anual aún no está disponible.</p>
        <?php endif; ?>

    </div>
</div>

<?php
include __DIR__ . "/../includes/footer.php";
?>