<?php
$ruta_revistas = __DIR__ . '/../../data/revistas.json';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'guardar_revista') {
    
    $revistas = file_exists($ruta_revistas) ? json_decode(file_get_contents($ruta_revistas), true) : [];
    
    // Calcular nuevo ID
    $nuevo_id = 1;
    if (count($revistas) > 0) {
        $ids = array_column($revistas, 'id');
        $nuevo_id = max($ids) + 1;
    }

    $ruta_imagen = "";
    if (isset($_FILES['portada_file']) && $_FILES['portada_file']['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = basename($_FILES['portada_file']['name']);
        $nombre_archivo = str_replace(" ", "_", $nombre_archivo);
        $ruta_destino = __DIR__ . '/../../assets/img/revista/' . $nombre_archivo;
        
        if (!is_dir(__DIR__ . '/../../assets/img/revista/')) {
            mkdir(__DIR__ . '/../../assets/img/revista/', 0777, true);
        }
        
        if (move_uploaded_file($_FILES['portada_file']['tmp_name'], $ruta_destino)) {
            $ruta_imagen = '../assets/img/revista/' . $nombre_archivo;
        }
    }

    $secciones_finales = [];
    if (isset($_POST['sec_titulo']) && is_array($_POST['sec_titulo'])) {
        foreach ($_POST['sec_titulo'] as $index => $titulo) {
            if (!empty(trim($titulo))) {
                $articulos_texto = $_POST['sec_articulos'][$index] ?? '';
                $articulos_array = array_filter(explode("\n", str_replace("\r", "", $articulos_texto)));
                $secciones_finales[] = [
                    "titulo" => $titulo,
                    "articulos" => array_values($articulos_array)
                ];
            }
        }
    }

    $nueva_revista = [
        "id" => $nuevo_id,
        "numero" => (int)$_POST['numero'],
        "anio" => $_POST['anio'],
        "portada" => $ruta_imagen,
        "precio" => $_POST['precio'],
        "secciones" => $secciones_finales
    ];

    $revistas[] = $nueva_revista;

    if (file_put_contents($ruta_revistas, json_encode($revistas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        $mensaje_exito = "¡Revista creada correctamente!";
    } else {
        $mensaje_error = "Error al guardar la revista.";
    }
}
?>

<h1>📄 Crear Nueva Revista</h1>
<p>Introduce los datos para añadir una nueva revista micológica al catálogo.</p>

<?php if (!empty($mensaje_exito)): ?>
    <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
<?php endif; ?>
<?php if (!empty($mensaje_error)): ?>
    <div class="alert alert-error"><?php echo $mensaje_error; ?></div>
<?php endif; ?>

<form action="?mod=revista-crear" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="guardar_revista">

    <div class="form-row">
        <div class="form-group" style="max-width: 150px;">
            <label>Número de Revista</label>
            <input type="number" name="numero" required>
        </div>
        <div class="form-group" style="max-width: 150px;">
            <label>Año</label>
            <input type="text" name="anio" required>
        </div>
        <div class="form-group" style="max-width: 150px;">
            <label>Precio</label>
            <input type="text" name="precio" placeholder="Ej: 7 €" required>
        </div>
        <div class="form-group">
            <label>Portada (Imagen JPG)</label>
            <input type="file" name="portada_file" accept="image/*" class="btn-file" required style="padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px; width: 100%;">
        </div>
    </div>

    <div class="section-divider">Sumario (Índice de Secciones)</div>
    
    <div id="contenedor-secciones">
        <div class="form-group" style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
            <label>Título de la Sección Principal (Ej: Rincón del socio)</label>
            <input type="text" name="sec_titulo[]" style="margin-bottom: 15px;" required>
            
            <label>Artículos de esta sección (<strong>Escribe uno por línea</strong>, y si hay autor ponlo entre paréntesis al final)</label>
            <textarea name="sec_articulos[]" style="min-height:120px;" placeholder="Actividades del 2003&#10;Hongos hipogeos... (Aurelio García Blanco)"></textarea>
        </div>
    </div>

    <button type="button" onclick="agregarSeccion()" style="background: #e2e8f0; color: #4a5568; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; margin-bottom: 30px;">
        + Añadir otra Sección
    </button>

    <div style="clear:both;"></div>
    <button type="submit" class="btn-save">💾 Guardar y Crear Revista</button>
</form>

<script>
    function agregarSeccion() {
        const contenedor = document.getElementById('contenedor-secciones');
        const nuevaSec = document.createElement('div');
        nuevaSec.className = 'form-group';
        nuevaSec.style.cssText = 'background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;';
        nuevaSec.innerHTML = `
            <label>Título de la Sección Principal</label>
            <input type="text" name="sec_titulo[]" style="margin-bottom: 15px;" required>
            <label>Artículos de esta sección (<strong>Uno por línea</strong>)</label>
            <textarea name="sec_articulos[]" style="min-height:120px;"></textarea>
        `;
        contenedor.appendChild(nuevaSec);
    }
</script>
