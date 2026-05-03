<?php
include __DIR__ . "/../../includes/header.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/concurso.css">
</head>

<main class="gallery-main">
    <section class="contest-section">
        <div class="container">
            <h2 class="section-title">Fotografías premiadas 2003</h2> <br>
            <p class="section-subtitle">Relación de fotografías premiadas en el III concurso de fotografía micológica
                "Errotari".</p>

            <div class="gallery-wrapper">

                <div class="main-image-display">
                    <img id="main-pict" src="<?php echo BASE_URL; ?>/assets/img/12003.jpg" alt="Fotografía Premiada">
                    <div id="main-author" class="image-author">Javier Gómez Fernández - Priego (Córdoba)</div>
                </div>

                <div class="thumbnails-grid">
                    <div class="thumb-item active" data-img="12003.jpg"
                        data-author="Javier Gómez Fernández - Priego (Córdoba)">
                        <img src="<?php echo BASE_URL; ?>/assets/img/12003.jpg" alt="1º Premio">
                        <span>1º Premio</span>
                    </div>
                    <div class="thumb-item" data-img="22003.jpg"
                        data-author="Mario Maguregui Arana - Zornotza (Bizkaia)">
                        <img src="<?php echo BASE_URL; ?>/assets/img/t22003.jpg" alt="2º Premio">
                        <span>2º Premio</span>
                    </div>
                    <div class="thumb-item" data-img="32003.jpg"
                        data-author="Carlos Sánchez Carcavilla - San Juan de Mozarrifar (Zaragoza)">
                        <img src="<?php echo BASE_URL; ?>/assets/img/t32003.jpg" alt="3º Premio">
                        <span>3º Premio</span>
                    </div>
                    <div class="thumb-item" data-img="42003.jpg"
                        data-author="José Manuel Ruiz Fernández - Bilbao (Bizkaia)">
                        <img src="<?php echo BASE_URL; ?>/assets/img/t42003.jpg" alt="4º Premio">
                        <span>4º Premio</span>
                    </div>
                    <div class="thumb-item" data-img="52003.jpg" data-author="Asier Ayala - Barakaldo (Bizkaia)">
                        <img src="<?php echo BASE_URL; ?>/assets/img/t52003.jpg" alt="5º Premio">
                        <span>5º Premio</span>
                    </div>
                    <div class="thumb-item" data-img="l2003.jpg"
                        data-author="Néstor Zubizarreta Hernández - Durango (Bizkaia)">
                        <img src="<?php echo BASE_URL; ?>/assets/img/tl2003.jpg" alt="Mejor local">
                        <span>Mejor local</span>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleccionamos todos los elementos necesarios
        const thumbs = document.querySelectorAll('.thumb-item');
        const mainImg = document.getElementById('main-pict');
        const mainAuthor = document.getElementById('main-author');

        // Ruta base para las imágenes grandes
        const imgBasePath = '<?php echo BASE_URL; ?>/assets/img/';

        thumbs.forEach(thumb => {
            thumb.addEventListener('click', function () {
                // Quitar la clase 'active' de todas las miniaturas
                thumbs.forEach(t => t.classList.remove('active'));

                // Añadir la clase 'active' solo a la que hemos hecho clic
                this.classList.add('active');

                // Cambiar la imagen principal y el texto del autor con un efecto sutil
                mainImg.style.opacity = 0; // Ocultamos rápido para transición

                setTimeout(() => {
                    mainImg.src = imgBasePath + this.getAttribute('data-img');
                    mainAuthor.textContent = this.getAttribute('data-author');
                    mainImg.style.opacity = 1; // Volvemos a mostrar
                }, 150);
            });
        });
    });
</script>

<?php
include __DIR__ . "/../../includes/footer.php";
?>