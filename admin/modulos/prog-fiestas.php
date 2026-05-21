<?php
$ruta_fiestas = __DIR__ . '/../../data/programa_fiestas.json';

// PROCESAR FORMULARIO CUANDO EL DUEÑO LE DA A "GUARDAR"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'guardar_fiestas') {

    // 1. GESTIÓN DE LA IMAGEN SUBIDA
    $ruta_imagen = $_POST['imagen_actual']; // Mantenemos la que había por si no sube una nueva

    // Si ha seleccionado un archivo y se ha subido correctamente...
    if (isset($_FILES['imagen_cartel_file']) && $_FILES['imagen_cartel_file']['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = basename($_FILES['imagen_cartel_file']['name']);
        // Limpiamos espacios en el nombre para evitar fallos en la web
        $nombre_archivo = str_replace(" ", "_", $nombre_archivo);
        $ruta_destino = __DIR__ . '/../../imagenes/' . $nombre_archivo;

        // Si la carpeta imagenes no existe, la crea automáticamente
        if (!is_dir(__DIR__ . '/../../imagenes/')) {
            mkdir(__DIR__ . '/../../imagenes/', 0777, true);
        }

        // Movemos el archivo de la memoria temporal a la carpeta final
        if (move_uploaded_file($_FILES['imagen_cartel_file']['tmp_name'], $ruta_destino)) {
            $ruta_imagen = '../imagenes/' . $nombre_archivo;
        }
    }

    // 2. GESTIÓN DEL CRONOGRAMA DINÁMICO (N Días)
    $cronograma_final = [];
    if (isset($_POST['fecha_dia']) && is_array($_POST['fecha_dia'])) {
        foreach ($_POST['fecha_dia'] as $index => $fecha) {
            if (!empty(trim($fecha))) { // Si la fecha no está vacía
                $eventos_texto = $_POST['eventos_dia'][$index] ?? '';
                $eventos_array = array_filter(explode("\n", str_replace("\r", "", $eventos_texto)));

                $cronograma_final[] = [
                    "fecha" => $fecha,
                    "eventos" => array_values($eventos_array)
                ];
            }
        }
    }

    // 3. RESTO DE TEXTOS
    $actividades_array = array_filter(explode("\n", str_replace("\r", "", $_POST['actividades_previstas'])));
    $bases_array = array_filter(explode("\n", str_replace("\r", "", $_POST['bases'])));

    // 4. CREAMOS EL NUEVO JSON
    $nuevo_fiestas = [
        "titulo_pagina" => $_POST['titulo_pagina'],
        "anio" => $_POST['anio'],
        "imagen_cartel" => $ruta_imagen,
        "info_evento" => [
            "nombre_fiesta" => $_POST['nombre_fiesta'],
            "organizador" => $_POST['organizador'],
            "lugar" => $_POST['lugar'],
            "fechas" => $_POST['fechas']
        ],
        "actividades_previstas" => array_values($actividades_array),
        "cronograma" => $cronograma_final,
        "concurso_fotografia" => [
            "nombre_concurso" => $_POST['nombre_concurso'],
            "direccion_entrega" => $_POST['direccion_entrega'],
            "telefonos_contacto" => $_POST['telefonos_contacto'],
            "bases" => array_values($bases_array)
        ]
    ];

    if (file_put_contents($ruta_fiestas, json_encode($nuevo_fiestas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        $mensaje_exito = "¡Programa de Fiestas actualizado correctamente!";
    } else {
        $mensaje_error = "Error al guardar el archivo JSON de Fiestas.";
    }
}

// LEER LOS DATOS ACTUALES DEL JSON PARA LLENAR EL FORMULARIO
$data_fiestas = file_exists($ruta_fiestas) ? json_decode(file_get_contents($ruta_fiestas), true) : [];
?>

<h1>🎉 Modificar Programa de Fiestas</h1>
<p>Gestiona los horarios, las actividades y sube el cartel de las fiestas.</p>

<?php if (!empty($mensaje_exito)): ?>
    <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
<?php endif; ?>
<?php if (!empty($mensaje_error)): ?>
    <div class="alert alert-error"><?php echo $mensaje_error; ?></div>
<?php endif; ?>

<form action="?mod=prog-fiestas" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="guardar_fiestas">

    <div class="form-row">
        <div class="form-group">
            <label>Título Principal de la Página</label>
            <input type="text" name="titulo_pagina"
                value="<?php echo htmlspecialchars($data_fiestas['titulo_pagina'] ?? ''); ?>" required>
        </div>
        <div class="form-group" style="max-width: 120px;">
            <label>Año</label>
            <input type="text" name="anio"
                value="<?php echo htmlspecialchars($data_fiestas['anio'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Cartel de Fiestas (Imagen)</label>

            <div class="image-edit-container">

                <?php if (!empty($data_fiestas['imagen_cartel'])): ?>
                    <div class="img-preview-box">
                        <img src="<?php echo htmlspecialchars($data_fiestas['imagen_cartel']); ?>"
                            alt="Cartel actual" class="img-preview">
                        <span style="font-size: 11px; color: #64748b; font-weight: 600;">Cartel actual</span>
                    </div>
                <?php endif; ?>

                <div class="file-upload-wrapper"
                    style="flex-direction: column; align-items: flex-start; gap: 8px;">
                    <span style="font-size: 13px; font-weight: 600; color: #4a5568;">¿Quieres cambiar el cartel?</span>

                    <label class="btn-file">
                        📁 Seleccionar nuevo archivo
                        <input type="file" name="imagen_cartel_file" accept="image/*" style="display: none;"
                            onchange="document.getElementById('file-name').textContent = this.files[0].name">
                    </label>
                    <span id="file-name" class="file-name-display">Ningún archivo seleccionado</span>
                </div>
            </div>
            <input type="hidden" name="imagen_actual"
                value="<?php echo htmlspecialchars($data_fiestas['imagen_cartel'] ?? ''); ?>">
        </div>
    </div>
    <div class="section-divider">Datos de la Cabecera del Evento</div>
    <div class="form-row">
        <div class="form-group">
            <label>Nombre de la Fiesta</label>
            <input type="text" name="nombre_fiesta"
                value="<?php echo htmlspecialchars($data_fiestas['info_evento']['nombre_fiesta'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Organiza</label>
            <input type="text" name="organizador"
                value="<?php echo htmlspecialchars($data_fiestas['info_evento']['organizador'] ?? ''); ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Lugar de Celebración</label>
            <input type="text" name="lugar"
                value="<?php echo htmlspecialchars($data_fiestas['info_evento']['lugar'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Fechas exactas</label>
            <input type="text" name="fechas"
                value="<?php echo htmlspecialchars($data_fiestas['info_evento']['fechas'] ?? ''); ?>">
        </div>
    </div>
    <div class="form-group">
        <label>Actividades Previstas destacadas (<strong>Escribe una por línea</strong>)</label>
        <textarea name="actividades_previstas"
            rows="5"><?php echo htmlspecialchars(implode("\n", $data_fiestas['actividades_previstas'] ?? [])); ?></textarea>
    </div>
    <div class="section-divider">Cronograma por Días</div>
    <div id="contenedor-cronograma">
        <?php
        $dias = $data_fiestas['cronograma'] ?? [['fecha' => '', 'eventos' => []]];
        foreach ($dias as $dia):
            ?>
            <div class="form-group"
                style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                <label>Fecha del Día (Ej: 8 DE OCTUBRE)</label>
                <input type="text" name="fecha_dia[]" value="<?php echo htmlspecialchars($dia['fecha']); ?>"
                    style="margin-bottom: 15px;">
                <label>Eventos del Día (<strong>Uno por línea</strong>)</label>
                <textarea name="eventos_dia[]"
                    style="min-height:120px;"><?php echo htmlspecialchars(implode("\n", $dia['eventos'])); ?></textarea>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" onclick="agregarDia()"
        style="background: #e2e8f0; color: #4a5568; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; margin-bottom: 30px;">
        + Añadir otro día al programa
    </button>
    <div class="section-divider">Bases del Concurso de Fotografía</div>
    <div class="form-group">
        <label>Nombre del Concurso</label>
        <input type="text" name="nombre_concurso"
            value="<?php echo htmlspecialchars($data_fiestas['concurso_fotografia']['nombre_concurso'] ?? ''); ?>">
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Dirección Postal de Entrega</label>
            <textarea name="direccion_entrega"
                style="min-height:80px;"><?php echo htmlspecialchars($data_fiestas['concurso_fotografia']['direccion_entrega'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label>Teléfonos de Contacto</label>
            <input type="text" name="telefonos_contacto"
                value="<?php echo htmlspecialchars($data_fiestas['concurso_fotografia']['telefonos_contacto'] ?? ''); ?>">
        </div>
    </div>
    <div class="form-group">
        <label>Bases Completas (<strong>Escribe una base entera por línea</strong>)</label>
        <textarea name="bases"
            style="min-height:200px;"><?php echo htmlspecialchars(implode("\n", $data_fiestas['concurso_fotografia']['bases'] ?? [])); ?></textarea>
    </div>
    <button type="submit" class="btn-save">💾 Guardar Programa de Fiestas</button>
</form>

<script>
    function agregarDia() {
        const contenedor = document.getElementById('contenedor-cronograma');
        const nuevoDia = document.createElement('div');
        nuevoDia.className = 'form-group';
        nuevoDia.style.cssText = 'background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;';
        nuevoDia.innerHTML = `
        <label>Fecha del Día (Ej: NUEVO DÍA)</label>
        <input type="text" name="fecha_dia[]" style="margin-bottom: 15px;">
        <label>Eventos del Día (<strong>Uno por línea</strong>)</label>
        <textarea name="eventos_dia[]" style="min-height:120px;"></textarea>
        `;
        contenedor.appendChild(nuevoDia);
    }
</script>
