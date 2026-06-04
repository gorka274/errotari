<?php
include __DIR__ . "/../includes/header.php";
?>
<link rel="stylesheet" href="../assets/css/blog.css">
<?php

$archivo_json = __DIR__ . '/../data/noticias.json';
$noticias = [];
if (file_exists($archivo_json)) {
    $json_data = file_get_contents($archivo_json);
    $noticias = json_decode($json_data, true) ?? [];
}
?>

<main class="blog-main-container">
    <div class="blog-header-section">
        <h1>Blog y Noticias</h1>
        <p>Entérate de las últimas novedades, actividades y descubrimientos de la Sociedad Micológica Errotari.</p>
    </div>

    <div class="noticias-grid">
        <?php if (empty($noticias)): ?>
            <p class="no-news">Actualmente no hay noticias publicadas. ¡Vuelve pronto!</p>
        <?php else: ?>
            <?php foreach ($noticias as $n): ?>
                <article class="noticia-card">
                    <div class="noticia-meta">
                        <span class="noticia-fecha">📅 <?php echo date('d/m/Y', strtotime($n['fecha'])); ?></span>
                    </div>
                    <h2 class="noticia-titulo"><?php echo htmlspecialchars($n['titulo']); ?></h2>
                    
                    <div class="noticia-resumen">
                        <?php echo nl2br(htmlspecialchars($n['resumen'])); ?>
                    </div>
                    
                    <?php if (!empty(trim($n['contenido']))): ?>
                        <div class="noticia-contenido" id="contenido-<?php echo $n['id']; ?>">
                            <?php echo nl2br(htmlspecialchars($n['contenido'])); ?>
                        </div>
                        <button class="btn-leer-mas" onclick="toggleContenido(<?php echo $n['id']; ?>)">
                            <span id="btn-text-<?php echo $n['id']; ?>">Leer más</span>
                        </button>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<script>
function toggleContenido(id) {
    const contenido = document.getElementById('contenido-' + id);
    const btnText = document.getElementById('btn-text-' + id);
    
    if (contenido.classList.contains('expandido')) {
        contenido.classList.remove('expandido');
        btnText.textContent = 'Leer más';
    } else {
        contenido.classList.add('expandido');
        btnText.textContent = 'Mostrar menos';
    }
}
</script>

<?php
include __DIR__ . "/../includes/footer.php";
?>