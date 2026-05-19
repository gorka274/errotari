<?php
include __DIR__ . "/../includes/header.php";
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/programas.css">

<?php
// Ruta relativa hacia el archivo JSON
$ruta_json_fiestas = __DIR__ . '/../data/programa_fiestas.json';
$fiestas = [];

if (file_exists($ruta_json_fiestas)) {
    $json_data = file_get_contents($ruta_json_fiestas);
    $fiestas = json_decode($json_data, true);
}
?>

<div class="cuerpo">
    <div class="cuerpo1">
        <div class="titulo">
            <?php echo htmlspecialchars($fiestas['titulo_pagina'] ?? 'Programa de Fiestas'); ?>
        </div>
        <p></p>

        <?php if (!empty($fiestas['imagen_cartel'])): ?>
            <div class="cuerpo1">
                <img src="<?php echo htmlspecialchars($fiestas['imagen_cartel']); ?>" width="700" align="bottom"
                    alt="Cartel de Fiestas">
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($fiestas['info_evento'])): ?>
        <div class="datos">
            <?php echo htmlspecialchars($fiestas['info_evento']['nombre_fiesta']); ?>
        </div>
        <div class="datos">
            Organizador:
            <?php echo htmlspecialchars($fiestas['info_evento']['organizador']); ?><br>
            Lugar:
            <?php echo htmlspecialchars($fiestas['info_evento']['lugar']); ?><br>
            FECHA:
            <?php echo htmlspecialchars($fiestas['info_evento']['fechas']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($fiestas['actividades_previstas'])): ?>
        <span class="tema">Actividades previstas:</span>
        <?php foreach ($fiestas['actividades_previstas'] as $actividad): ?>
            <div class="texto2c">&middot;
                <?php echo htmlspecialchars($actividad); ?>
            </div>
        <?php endforeach; ?>
        <br>
    <?php endif; ?>

    <?php if (!empty($fiestas['cronograma'])): ?>
        <?php foreach ($fiestas['cronograma'] as $dia): ?>
            <div class="ch3">
                <?php echo htmlspecialchars($dia['fecha']); ?>
            </div>

            <?php foreach ($dia['eventos'] as $evento): ?>
                <p>
                    <?php echo nl2br(htmlspecialchars($evento)); ?>
                </p>
            <?php endforeach; ?>

        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($fiestas['concurso_fotografia'])): ?>
        <span class="tema">
            <?php echo htmlspecialchars($fiestas['concurso_fotografia']['nombre_concurso']); ?>
        </span>
        <div class="th2">BASES</div>
        <br><br><br>

        <p class="texto">
            <?php foreach ($fiestas['concurso_fotografia']['bases'] as $base): ?>
                <?php echo nl2br(htmlspecialchars($base)); ?><br><br>
            <?php endforeach; ?>
        </p>

        <div class="datos">
            <?php echo nl2br(htmlspecialchars($fiestas['concurso_fotografia']['direccion_entrega'])); ?>
        </div>
        <br>

        <p class="texto">
            Si hubiera alguna duda se podría llamar a uno de estos números de teléfono:<br>
            <?php echo htmlspecialchars($fiestas['concurso_fotografia']['telefonos_contacto']); ?>
        </p>
    <?php endif; ?>
</div>

<?php
include __DIR__ . "/../includes/footer.php";
?>