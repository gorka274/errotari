<?php
// Evitar acceso directo
if (!isset($_SESSION['admin'])) {
    exit;
}

$archivo_json = __DIR__ . '/../../data/noticias.json';

// Cargar noticias actuales
$noticias = [];
if (file_exists($archivo_json)) {
    $json_data = file_get_contents($archivo_json);
    $noticias = json_decode($json_data, true) ?? [];
}

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    if ($accion === 'crear') {
        $nuevo_id = empty($noticias) ? 1 : max(array_column($noticias, 'id')) + 1;
        $nueva_noticia = [
            'id' => $nuevo_id,
            'titulo' => trim($_POST['titulo']),
            'fecha' => trim($_POST['fecha']),
            'resumen' => trim($_POST['resumen']),
            'contenido' => trim($_POST['contenido'])
        ];
        array_unshift($noticias, $nueva_noticia); // Añadir al principio (más reciente primero)
        
        // Ordenar por fecha desc (por si se editan o añaden fechas antiguas)
        usort($noticias, function($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });

        file_put_contents($archivo_json, json_encode($noticias, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $mensaje_exito = "Noticia creada correctamente.";
    } 
    elseif ($accion === 'editar') {
        $id_editar = intval($_POST['id']);
        foreach ($noticias as &$n) {
            if ($n['id'] === $id_editar) {
                $n['titulo'] = trim($_POST['titulo']);
                $n['fecha'] = trim($_POST['fecha']);
                $n['resumen'] = trim($_POST['resumen']);
                $n['contenido'] = trim($_POST['contenido']);
                break;
            }
        }
        
        usort($noticias, function($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });

        file_put_contents($archivo_json, json_encode($noticias, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $mensaje_exito = "Noticia modificada correctamente.";
    }
    elseif ($accion === 'borrar') {
        $id_borrar = intval($_POST['id']);
        $noticias = array_filter($noticias, function($n) use ($id_borrar) {
            return $n['id'] !== $id_borrar;
        });
        $noticias = array_values($noticias); // Reindexar
        file_put_contents($archivo_json, json_encode($noticias, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $mensaje_exito = "Noticia eliminada correctamente.";
    }
}

// Variables para el formulario de edición
$noticia_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = intval($_GET['editar']);
    foreach ($noticias as $n) {
        if ($n['id'] === $id_editar) {
            $noticia_editar = $n;
            break;
        }
    }
}
?>

<div class="modulo-header">
    <h3>Gestión del Blog (Noticias)</h3>
</div>

<?php if (!empty($mensaje_exito)): ?>
    <div class="mensaje exito"><?php echo htmlspecialchars($mensaje_exito); ?></div>
<?php endif; ?>
<?php if (!empty($mensaje_error)): ?>
    <div class="mensaje error"><?php echo htmlspecialchars($mensaje_error); ?></div>
<?php endif; ?>

<!-- Formulario (Crear o Editar) -->
<div class="form-container" style="background:#f9fafb; padding:20px; border-radius:8px; margin-bottom:30px; border:1px solid #e5e7eb;">
    <h4 style="margin-top:0; color:#1e3f20;"><?php echo $noticia_editar ? '✏️ Modificar Noticia' : '📝 Publicar Nueva Noticia'; ?></h4>
    <form method="POST" action="?mod=prog-blog">
        <input type="hidden" name="accion" value="<?php echo $noticia_editar ? 'editar' : 'crear'; ?>">
        <?php if ($noticia_editar): ?>
            <input type="hidden" name="id" value="<?php echo $noticia_editar['id']; ?>">
        <?php endif; ?>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold; color:#374151;">Título</label>
            <input type="text" name="titulo" required style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" value="<?php echo $noticia_editar ? htmlspecialchars($noticia_editar['titulo']) : ''; ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold; color:#374151;">Fecha</label>
            <input type="date" name="fecha" required style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" value="<?php echo $noticia_editar ? htmlspecialchars($noticia_editar['fecha']) : date('Y-m-d'); ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold; color:#374151;">Resumen (Breve descripción que se ve en las tarjetas)</label>
            <textarea name="resumen" required style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px; height:80px; font-family:inherit; resize:vertical;"><?php echo $noticia_editar ? htmlspecialchars($noticia_editar['resumen']) : ''; ?></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold; color:#374151;">Contenido Completo (Se despliega al dar a "Leer más")</label>
            <textarea name="contenido" required style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px; height:200px; font-family:inherit; resize:vertical;"><?php echo $noticia_editar ? htmlspecialchars($noticia_editar['contenido']) : ''; ?></textarea>
        </div>

        <div>
            <button type="submit" class="btn-submit" style="background-color: #2e6f40; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight:bold;">
                <?php echo $noticia_editar ? 'Guardar Cambios' : 'Publicar Noticia'; ?>
            </button>
            <?php if ($noticia_editar): ?>
                <a href="?mod=prog-blog" style="margin-left: 15px; color: #6b7280; text-decoration: none; font-weight:bold;">Cancelar edición</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Listado de Noticias -->
<h4 style="color:#1e3f20;">Noticias Publicadas</h4>
<?php if (empty($noticias)): ?>
    <p style="color:#6b7280; margin-top:10px;">Aún no hay noticias publicadas.</p>
<?php else: ?>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse: collapse; margin-top:15px; text-align:left; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); border-radius:8px; overflow:hidden;">
            <thead>
                <tr style="background-color: #f1f5f9; border-bottom: 2px solid #cbd5e1;">
                    <th style="padding: 15px; color:#475569;">Fecha</th>
                    <th style="padding: 15px; color:#475569;">Título</th>
                    <th style="padding: 15px; color:#475569; text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($noticias as $n): ?>
                    <tr style="border-bottom: 1px solid #e2e8f0; transition:background-color 0.2s;">
                        <td style="padding: 15px; width: 120px; color:#64748b;"><?php echo date('d/m/Y', strtotime($n['fecha'])); ?></td>
                        <td style="padding: 15px; font-size:1.05rem; color:#1e293b;"><strong><?php echo htmlspecialchars($n['titulo']); ?></strong></td>
                        <td style="padding: 15px; width: 200px; text-align:right;">
                            <a href="?mod=prog-blog&editar=<?php echo $n['id']; ?>" style="color: #2563eb; text-decoration: none; margin-right: 15px; font-weight:500;">✏️ Editar</a>
                            <form method="POST" action="?mod=prog-blog" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que quieres borrar esta noticia definitivamente?');">
                                <input type="hidden" name="accion" value="borrar">
                                <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
                                <button type="submit" style="background:none; border:none; color: #dc2626; cursor:pointer; font-size: 15px; font-weight:500; font-family:inherit;">🗑️ Borrar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
