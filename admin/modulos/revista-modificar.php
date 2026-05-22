<?php
$ruta_revistas = __DIR__ . '/../../data/revistas.json';
$revistas = file_exists($ruta_revistas) ? json_decode(file_get_contents($ruta_revistas), true) : [];

// Actualizar Revista
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'actualizar_revista') {
    $id_editar = (int)$_POST['id_revista'];
    
    // Find index
    $idx = null;
    foreach ($revistas as $i => $r) {
        if ($r['id'] === $id_editar) {
            $idx = $i;
            break;
        }
    }

    if ($idx !== null) {
        $ruta_imagen = $_POST['portada_actual'];
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

        $revistas[$idx] = [
            "id" => $id_editar,
            "numero" => (int)$_POST['numero'],
            "anio" => $_POST['anio'],
            "portada" => $ruta_imagen,
            "precio" => $_POST['precio'],
            "secciones" => $secciones_finales
        ];

        if (file_put_contents($ruta_revistas, json_encode($revistas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            $mensaje_exito = "¡Revista actualizada correctamente!";
        } else {
            $mensaje_error = "Error al guardar los cambios en la revista.";
        }
    }
}

// Check if we are editing
$id_editar = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$revista_editar = null;
if ($id_editar) {
    foreach ($revistas as $r) {
        if ($r['id'] === $id_editar) {
            $revista_editar = $r;
            break;
        }
    }
}
?>

<?php if (!$id_editar): ?>
    <h1>✏️ Modificar o Eliminar Revistas</h1>
    <p>Selecciona una revista de la lista para modificar su contenido o su portada.</p>
    
    <?php if (!empty($mensaje_exito)): ?>
        <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
    <?php endif; ?>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f1f5f9; text-align: left;">
                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Número</th>
                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Año</th>
                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Precio</th>
                <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($revistas as $r): ?>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px;"><strong>Nº <?php echo htmlspecialchars($r['numero']); ?></strong></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($r['anio']); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($r['precio']); ?></td>
                    <td style="padding: 12px;">
                        <a href="?mod=revista-modificar&edit=<?php echo $r['id']; ?>" style="display: inline-block; background-color: var(--primary-color); color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: bold;">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <?php if ($revista_editar): ?>
        <h1>✏️ Editando Revista Nº <?php echo htmlspecialchars($revista_editar['numero']); ?></h1>
        <p><a href="?mod=revista-modificar" style="color: #64748b; text-decoration: none;">&larr; Volver a la lista</a></p>

        <?php if (!empty($mensaje_exito)): ?>
            <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>
        <?php if (!empty($mensaje_error)): ?>
            <div class="alert alert-error"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>

        <form action="?mod=revista-modificar&edit=<?php echo $revista_editar['id']; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="actualizar_revista">
            <input type="hidden" name="id_revista" value="<?php echo $revista_editar['id']; ?>">

            <div class="form-row">
                <div class="form-group" style="max-width: 150px;">
                    <label>Número de Revista</label>
                    <input type="number" name="numero" value="<?php echo htmlspecialchars($revista_editar['numero']); ?>" required>
                </div>
                <div class="form-group" style="max-width: 150px;">
                    <label>Año</label>
                    <input type="text" name="anio" value="<?php echo htmlspecialchars($revista_editar['anio']); ?>" required>
                </div>
                <div class="form-group" style="max-width: 150px;">
                    <label>Precio</label>
                    <input type="text" name="precio" value="<?php echo htmlspecialchars($revista_editar['precio']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Cambiar Portada</label>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <img src="<?php echo htmlspecialchars($revista_editar['portada']); ?>" width="50" style="border-radius: 4px; border: 1px solid #ccc;">
                        <input type="file" name="portada_file" accept="image/*" class="btn-file" style="padding: 9px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    </div>
                    <input type="hidden" name="portada_actual" value="<?php echo htmlspecialchars($revista_editar['portada']); ?>">
                </div>
            </div>

            <div class="section-divider">Sumario (Índice de Secciones)</div>
            
            <div id="contenedor-secciones">
                <?php foreach ($revista_editar['secciones'] as $seccion): ?>
                    <div class="form-group" style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                        <label>Título de la Sección Principal</label>
                        <input type="text" name="sec_titulo[]" value="<?php echo htmlspecialchars($seccion['titulo']); ?>" style="margin-bottom: 15px;" required>
                        
                        <label>Artículos de esta sección (<strong>Escribe uno por línea</strong>)</label>
                        <textarea name="sec_articulos[]" style="min-height:120px;"><?php echo htmlspecialchars(implode("\n", $seccion['articulos'])); ?></textarea>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" onclick="agregarSeccion()" style="background: #e2e8f0; color: #4a5568; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; margin-bottom: 30px;">
                + Añadir otra Sección
            </button>

            <div style="clear:both;"></div>
            <button type="submit" class="btn-save">💾 Guardar Cambios</button>
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
    <?php else: ?>
        <h1>Revista no encontrada</h1>
        <p>No se ha podido localizar esta revista en la base de datos.</p>
    <?php endif; ?>
<?php endif; ?>
