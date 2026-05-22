<?php
include __DIR__ . "/../includes/header.php";

$ruta_revistas = __DIR__ . '/../data/revistas.json';
// Leemos el JSON y lo convertimos en array
$revistas = file_exists($ruta_revistas) ? json_decode(file_get_contents($ruta_revistas), true) : [];

// Ordenamos las revistas de mayor a menor (de la más nueva a la más antigua)
usort($revistas, function($a, $b) {
    return $b['numero'] <=> $a['numero'];
});
?>

<head>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/catalogo.css">
</head>

<main class="catalogo-main">
    <section class="container text-center">
        <h1 style="margin-bottom: 40px;">Catálogo de Revistas Errotari</h1>
        
        <div class="grid-revistas">
            <?php if (!empty($revistas)): ?>
                <?php foreach ($revistas as $revista): ?>
                    <div class="tarjeta-revista">
                        <a href="revista.php?id=<?php echo $revista['id']; ?>">
                            <img src="<?php echo htmlspecialchars($revista['portada']); ?>" 
                                 alt="Portada Revista nº <?php echo htmlspecialchars($revista['numero']); ?>" 
                                 class="portada-miniatura" loading="lazy">
                        </a>
                        <h3>Revista nº <?php echo htmlspecialchars($revista['numero']); ?></h3>
                        <p>Año <?php echo htmlspecialchars($revista['anio']); ?></p>
                        <a href="revista.php?id=<?php echo $revista['id']; ?>" class="btn-ver">Ver sumario</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aún no hay revistas disponibles en el catálogo.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
include __DIR__ . "/../includes/footer.php";
?>