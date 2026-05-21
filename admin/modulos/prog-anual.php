<?php
$ruta_anual = __DIR__ . '/../../data/programa_anual.json';

// PROCESAR FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'guardar_anual') {
    $nuevo_anual = [
        "titulo" => $_POST['titulo'] ?? '',
        "anio" => $_POST['anio'] ?? '',
        "contenido_bloques" => [
            [
                "tipo" => "parrafo",
                "texto" => $_POST['bloque_1'] ?? ''
            ],
            [
                "tipo" => "seccion",
                "titulo_seccion" => $_POST['bloque_2_titulo'] ?? '',
                "texto" => $_POST['bloque_2_texto'] ?? ''
            ],
            [
                "tipo" => "parrafo",
                "texto" => $_POST['bloque_3'] ?? ''
            ],
            [
                "tipo" => "parrafo",
                "texto" => $_POST['bloque_4'] ?? ''
            ]
        ]
    ];

    if (file_put_contents($ruta_anual, json_encode($nuevo_anual, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        $mensaje_exito = "¡Programa Anual actualizado correctamente!";
    } else {
        $mensaje_error = "Error al guardar el archivo JSON del Programa Anual.";
    }
}

// LEER LOS DATOS ACTUALES DEL JSON
$data_anual = file_exists($ruta_anual) ? json_decode(file_get_contents($ruta_anual), true) : [];
?>
<h1>🗓️ Modificar Programa Anual</h1>
<p>Modifica los textos informativos de las actividades anuales de los socios.</p>

<?php if (!empty($mensaje_exito)): ?>
    <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
<?php endif; ?>
<?php if (!empty($mensaje_error)): ?>
    <div class="alert alert-error"><?php echo $mensaje_error; ?></div>
<?php endif; ?>

<form action="?mod=prog-anual" method="POST">
    <input type="hidden" name="action" value="guardar_anual">

    <div class="form-row">
        <div class="form-group">
            <label>Título de la sección</label>
            <input type="text" name="titulo"
                value="<?php echo htmlspecialchars($data_anual['titulo'] ?? ''); ?>" required>
        </div>
        <div class="form-group" style="max-width: 150px;">
            <label>Año vigente</label>
            <input type="text" name="anio"
                value="<?php echo htmlspecialchars($data_anual['anio'] ?? ''); ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label>Bloque 1: Texto Introducción</label>
        <textarea
            name="bloque_1"><?php echo htmlspecialchars($data_anual['contenido_bloques'][0]['texto'] ?? ''); ?></textarea>
    </div>

    <div class="section-divider">Bloque 2: Sección Destacada</div>
    <div class="form-row">
        <div class="form-group" style="max-width: 250px;">
            <label>Título de la Alerta (Ej: Octubre:)</label>
            <input type="text" name="bloque_2_titulo"
                value="<?php echo htmlspecialchars($data_anual['contenido_bloques'][1]['titulo_seccion'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Cuerpo de texto de la alerta</label>
            <textarea name="bloque_2_texto"
                style="min-height: 120px;"><?php echo htmlspecialchars($data_anual['contenido_bloques'][1]['texto'] ?? ''); ?></textarea>
        </div>
    </div>

    <div class="section-divider">Bloques de Cierre</div>
    <div class="form-group">
        <label>Bloque 3: Información de Cursillos y Teléfonos</label>
        <textarea
            name="bloque_3"><?php echo htmlspecialchars($data_anual['contenido_bloques'][2]['texto'] ?? ''); ?></textarea>
    </div>
    <div class="form-group">
        <label>Bloque 4: Nota final al pie</label>
        <textarea
            name="bloque_4"><?php echo htmlspecialchars($data_anual['contenido_bloques'][3]['texto'] ?? ''); ?></textarea>
    </div>

    <button type="submit" class="btn-save">💾 Guardar Cambios</button>
</form>
