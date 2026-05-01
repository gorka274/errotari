<?php
include __DIR__ . "/includes/header.php";
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/index.css">
</head>


<main class="home-main">
    <section class="welcome-section">
        <div class="container">
            <h1>Bienvenido a la Sociedad Micológica Errotari</h1>
            <p>Pasión por la micología en el corazón de Durango desde el año 2000.</p>
        </div>
    </section>

    <section class="history-section">
        <div class="container">
            <div class="history-grid">

                <div class="history-text">
                    <h2 class="section-title">Historia de la sociedad</h2>
                    <p>El grupo micológico <strong>Errotari</strong> de Durango nació en el año 2000 ante la dificultad
                        de los durangueses para encontrar en el pueblo información sobre este tema.</p>

                    <p>Siete amigos comenzamos a reunirnos en tabernas del pueblo con la intención de formar una fuerte
                        asociación. Antes que nada, era necesario elegir un nombre. El nombre que escogimos después de
                        mucho hablar no es un nombre cualquiera: <strong>Errotari</strong>, <em>"Clitopilus
                            prunulus"</em>, buen comestible. Delatora de los boletos, molinera en castellano, panadera,
                        y coloquialmente, en la calle, chivata.</p>

                    <p>Seta humilde y pequeña, como nuestro origen. A pesar de tener pocos recursos enseguida preparamos
                        nuestra primera exposición en las fiestas de San Fausto del 2000. La multitud que acudió y el
                        rápido crecimiento del grupo nos llenaron de esperanza.</p>

                    <p>Hoy en día somos más de <strong>390 socios</strong>. Estamos orgullosos de nuestros avances,
                        especialmente de la labor que realizamos con los niños y niñas para impulsar su afición
                        micológica y cuidar la pluralidad de la asociación.</p>

                    <p>Nuestro concurso fotográfico recibe imágenes de gran calidad de todo el Estado, dando a conocer
                        nuestra asociación y nuestro pueblo. Micólogos de renombre acuden a nuestras actividades, junto
                        a gente de todas las edades.</p>

                    <p>Dedicamos esta web a nuestros socios y a todos los durangueses. Gracias a vuestro esfuerzo
                        desinteresado, hoy ofrecemos nuestro trabajo al mundo entero mediante esta aventura digital.</p>
                </div>

                <div class="history-image">
                    <div class="image-wrapper">
                        <img src="<?php echo BASE_URL; ?>/assets/img/logo.png"
                            alt="Seta Errotari - Clitopilus prunulus">
                        <span><em>Clitopilus prunulus</em> (Errotari)</span>
                    </div>
                </div>

            </div>
        </div>
    </section>



    <section class="objectives-section">
        <div class="container">
            <h2 class="section-title">Objetivos</h2>

            <ul class="objectives-list">
                <li>Disminuir el número de intoxicaciones ofreciendo un servicio de consulta.</li>
                <li>Aprender y enseñar sobre temas de micología, fomentando el respeto por la naturaleza, a ser posible
                    desde joven.</li>
                <li>Estudio y catalogación de la flora micológica local.</li>
            </ul>

            <div class="objectives-gallery">
                <div class="gallery-item">
                    <img src="<?php echo BASE_URL; ?>/assets/img/foto1.jpg"
                        alt="Exposición y concurso fotográfico del 2003">
                    <p class="image-caption">Exposición y concurso fotográfico del 2003</p>
                </div>

                <div class="gallery-item">
                    <img src="<?php echo BASE_URL; ?>/assets/img/foto2.jpg" alt="Jugando con los niños">
                    <p class="image-caption">Jugando con los niños</p>
                </div>
            </div>
        </div>
    </section>


    <section class="location-section">
        <div class="container">
            <h2 class="section-title">Dónde estamos</h2>
            <p style="margin-bottom: 20px; color: #555;">Nos reunimos en Laubideta, 6 (Durango). ¡Pásate a conocernos!
            </p>

            <div class="map-container">
                <iframe
                    src="https://maps.google.com/maps?q=Laubideta%206,%20Durango,%20Bizkaia&t=&z=16&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

</main>

<?php
include __DIR__ . "/includes/footer.php";
?>