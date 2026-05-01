<?php
include __DIR__ . "/includes/header.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/contacto.css">
</head>

<section class="partners-section">
    <div class="container">
        <h2 class="section-title">Nuevos Socios</h2> <br>
        <p class="form-intro">Si deseas pertenecer a la Asociación Micológica Errotari rellena el formulario y nos
            pondremos en contacto contigo. Cuota anual 15&euro;.</p>

        <div class="billing-form-container">
            <form action="#" method="POST" class="modern-form">

                <div class="form-row split-row">
                    <label>Nombre<span class="req">*</span></label>
                    <input type="text" name="nombre" required>

                    <label class="label-right">Apellidos<span class="req">*</span></label>
                    <input type="text" name="apellidos" required>
                </div>

                <div class="form-row">
                    <label>Dirección<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="direccion" placeholder="Número y nombre de la calle" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Población<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="poblacion" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Provincia<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="provincia" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>C.P.<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="cp" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Teléfono<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="tel" name="telefono" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Email<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="email" name="email" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Enviar Formulario</button>
                    <button type="reset" class="btn-reset">Borrar</button>
                </div>

            </form>
        </div>
    </div>
</section>

<?php
include __DIR__ . "/includes/footer.php";
?>