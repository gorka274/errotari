<?php
include __DIR__ . "/../includes/header.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/revista.css">
</head>

<main class="magazine-main">
    <section class="magazine-section">
        <div class="container">

            <h2 class="section-title text-center" style="text-align: center; margin-bottom: 50px;">Revista micológica
                "Errotari" nº 1 - Año 2004</h2>

            <div class="magazine-layout">

                <aside class="magazine-sidebar">
                    <div class="cover-image">
                        <img src="<?php echo BASE_URL; ?>/assets/img/revista/portada1.jpg"
                            alt="Portada de la Revista Errotari nº 1 del año 2004" width="283" height="400">
                    </div>

                    <div class="purchase-info">
                        <span class="info-icon"></span>
                        <p>La revista se distribuye de forma gratuita entre los socios, se intercambia con publicaciones
                            de otras asociaciones o puede ser adquirida a un precio de <strong>7 €</strong> más gastos
                            de envío. Solicitudes a través de nuestro correo electrónico.</p>
                    </div>
                </aside>

                <article class="magazine-toc" id="contenido-pdf">

                    <div class="toc-header">
                        <h3 class="toc-title">Sumario</h3>
                        <button id="btn-pdf" class="btn-descarga" onclick="descargarPDF()">
                            📄 Descargar en PDF
                        </button>
                    </div>

                    <ol class="main-toc">
                        <li><strong>Presentación</strong></li>

                        <li><strong>Rincón del socio</strong>
                            <ol class="sub-toc">
                                <li>Actividades del 2003</li>
                                <li>Excursiones realizadas en el 2003</li>
                                <li>Exposición micológica. Relación de especies</li>
                                <li>Concurso fotográfico</li>
                                <li>Proyectos 2004</li>
                            </ol>
                        </li>

                        <li><strong>Informática</strong>
                            <ol class="sub-toc">
                                <li>Página web de Errotari</li>
                                <li>Enlaces micológicos de interés en Internet</li>
                            </ol>
                        </li>

                        <li><strong>Micología - Colaboraciones</strong>
                            <ol class="sub-toc">
                                <li><em>Hidropus trichoderma v. lobauensis</em> <span class="author">(Giovanni
                                        Robich)</span></li>
                                <li>Hongos hipogeos... <span class="author">(Aurelio García Blanco)</span></li>
                                <li><em>Amanita friabilis...</em> <span class="author">(Javier Fernández Vicente y
                                        Joserra Udagoitia)</span></li>
                                <li><em>Tricholoma luridum...</em> <span class="author">(Javier Fernández Vicente y
                                        Joserra Udagoitia)</span></li>
                                <li><em>Pulvinula ovalispora...</em> <span class="author">(Javier Fernández Vicente y
                                        Joserra Udagoitia)</span></li>
                                <li><em>Xerocomus ripariellus...</em> <span class="author">(Javier Fernández Vicente y
                                        José Luis Pérez Butrón)</span></li>
                                <li><em>Helvella helvellula...</em> <span class="author">(J. M. Ruiz)</span></li>
                                <li><em>Lyophyllum ochraceum...</em> <span class="author">(Sabino Arauzo)</span></li>
                                <li><em>Tricholoma josserandii...</em> <span class="author">(Plácido Iglesias)</span>
                                </li>
                            </ol>
                        </li>

                        <li><strong>Gastronomía</strong> <span class="author">(J. C. Pérez - Joseba Ortiz de Zárate -
                                Javi Landa)</span>
                            <ol class="sub-toc">
                                <li>Pastel de setas con salsa de senderuelas</li>
                                <li>Champiñones guisados</li>
                                <li>Albóndigas de rape con setas</li>
                                <li>Crepes de setas</li>
                                <li>Peras al vino con natillas y chocolate</li>
                            </ol>
                        </li>
                    </ol>
                </article>

            </div>
        </div>
    </section>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function descargarPDF() {
        const elemento = document.getElementById('contenido-pdf');
        const boton = document.getElementById('btn-pdf');

        // Ocultamos el botón
        boton.style.display = 'none';

        // Configuramos cómo queremos el PDF
        const opciones = {
            margin: 15,
            filename: 'Sumario_Errotari_2004.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: {
                scale: 2,
                scrollY: 0
            },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            // --- AQUÍ ESTÁ LA SOLUCIÓN AL CORTE ---
            pagebreak: { avoid: 'li' }
        };

        // Generamos y descargamos
        html2pdf().set(opciones).from(elemento).save().then(() => {
            // Volvemos a mostrar el botón
            boton.style.display = 'inline-flex';
        });
    }
</script>

<?php
include __DIR__ . "/../includes/footer.php";
?>