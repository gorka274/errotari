<?php
// Cargar PHPMailer
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recoger y limpiar los datos del formulario
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $apellidos = htmlspecialchars(trim($_POST['apellidos']));
    $direccion = htmlspecialchars(trim($_POST['direccion']));
    $poblacion = htmlspecialchars(trim($_POST['poblacion']));
    $provincia = htmlspecialchars(trim($_POST['provincia']));
    $cp = htmlspecialchars(trim($_POST['cp']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $email_usuario = htmlspecialchars(trim($_POST['email']));
    
    // Validar que los campos requeridos no estén vacíos
    if(empty($nombre) || empty($apellidos) || empty($direccion) || empty($poblacion) || empty($provincia) || empty($cp) || empty($telefono) || empty($email_usuario)) {
        $error = "Por favor, complete todos los campos obligatorios.";
    } elseif (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
        $error = "Por favor, introduzca un email válido.";
    } else {
        
        // ---------------- CONFIGURACIÓN DEL CORREO ----------------
        // ¡¡¡ SOLO CAMBIA LAS 4 COSAS DE ABAJO !!!
        
        $mail = new PHPMailer(true);
        
        try {
            // Configuración del servidor SMTP (usando Gmail)
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Cambia a DEBUG_SERVER si quieres ver errores
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'a_arancibiamor@fpzornotzalh.eus';     // ← 1. CAMBIA: Tu email de Gmail
            $mail->Password   = 'ubohyiebbcnzfepo';          // ← 2. CAMBIA: Tu contraseña de Gmail (o app password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            
            // Remitente FIJO (el que aparecerá como remitente)
            $mail->setFrom('a_arancibiamor@fpzornotzalh.eus', 'Asociación Errotari'); // ← 3. CAMBIA: Mismo email que arriba
            
            // Destinatario (a quién le llega el email)
            $mail->addAddress('a_arancibiamor@fpzornotzalh.eus', 'Asociación Errotari'); // ← 4. CAMBIA: Email que recibirá los datos
            
            // Opcional: Para poder responder al usuario fácilmente
            $mail->addReplyTo($email_usuario, "$nombre $apellidos");
            
            // Contenido del email
            $mail->isHTML(true);
            $mail->Subject = 'Nuevo socio - Formulario de inscripción';
            
            // Cuerpo del mensaje en HTML
            $mail->Body = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Nuevo socio</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; }
                    .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                    .info-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .info-table td { padding: 12px; border-bottom: 1px solid #ddd; }
                    .label { font-weight: bold; background-color: #f0f0f0; width: 120px; }
                    .footer { text-align: center; padding: 15px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Asociación Micológica Errotari</h2>
                        <p>Nuevo socio registrado</p>
                    </div>
                    <div class='content'>
                        <h3>Datos del nuevo socio:</h3>
                        <table class='info-table'>
                            <tr><td class='label'>Nombre completo:</td><td>$nombre $apellidos</td></tr>
                            <tr><td class='label'>Dirección:</td><td>$direccion</td></tr>
                            <tr><td class='label'>Población:</td><td>$poblacion</td></tr>
                            <tr><td class='label'>Provincia:</td><td>$provincia</td></tr>
                            <tr><td class='label'>Código Postal:</td><td>$cp</td></tr>
                            <tr><td class='label'>Teléfono:</td><td>$telefono</td></tr>
                            <tr><td class='label'>Email:</td><td>$email_usuario</td></tr>
                        </table>
                        <br>
                        <p><strong>Cuota anual: 15€</strong></p>
                        <p>Por favor, contactar con el nuevo socio para completar el proceso de inscripción.</p>
                    </div>
                    <div class='footer'>
                        <p>Este mensaje fue enviado automáticamente desde el formulario de la web.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            // Versión en texto plano (para clientes que no soportan HTML)
            $mail->AltBody = "Nuevo socio - Asociación Micológica Errotari\n\nDatos:\nNombre: $nombre $apellidos\nDirección: $direccion\nPoblación: $poblacion\nProvincia: $provincia\nCP: $cp\nTeléfono: $telefono\nEmail: $email_usuario\n\nCuota anual: 15€";
            
            // Enviar el correo
            $mail->send();
            $exito = "¡Gracias por tu interés, $nombre! Nos pondremos en contacto contigo pronto.";
            
            // Limpiar variables del formulario
            $nombre = $apellidos = $direccion = $poblacion = $provincia = $cp = $telefono = $email_usuario = "";
            
        } catch (Exception $e) {
            $error = "Error al enviar el formulario: " . $mail->ErrorInfo;
        }
    }
}

// Incluir el header del sitio
include __DIR__ . "/includes/header.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/contacto.css">
    <style>
        /* Estilos para los mensajes de alerta */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            animation: fadeIn 0.5s ease-in;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<section class="partners-section">
    <div class="container">
        <h2 class="section-title">Nuevos Socios</h2> <br>
        <p class="form-intro">Si deseas pertenecer a la Asociación Micológica Errotari rellena el formulario y nos
            pondremos en contacto contigo. Cuota anual 15&euro;.</p>

        <!-- Mostrar mensaje de éxito si existe -->
        <?php if(isset($exito) && !empty($exito)): ?>
            <div class="alert alert-success">
                <?php echo $exito; ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar mensaje de error si existe -->
        <?php if(isset($error) && !empty($error)): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="billing-form-container">
            <form action="" method="POST" class="modern-form">

                <div class="form-row split-row">
                    <label>Nombre<span class="req">*</span></label>
                    <input type="text" name="nombre" value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>" required>

                    <label class="label-right">Apellidos<span class="req">*</span></label>
                    <input type="text" name="apellidos" value="<?php echo isset($apellidos) ? htmlspecialchars($apellidos) : ''; ?>" required>
                </div>

                <div class="form-row">
                    <label>Dirección<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="direccion" placeholder="Número y nombre de la calle" value="<?php echo isset($direccion) ? htmlspecialchars($direccion) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Población<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="poblacion" value="<?php echo isset($poblacion) ? htmlspecialchars($poblacion) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Provincia<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="provincia" value="<?php echo isset($provincia) ? htmlspecialchars($provincia) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>C.P.<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="cp" value="<?php echo isset($cp) ? htmlspecialchars($cp) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Teléfono<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="tel" name="telefono" value="<?php echo isset($telefono) ? htmlspecialchars($telefono) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <label>Email<span class="req">*</span></label>
                    <div class="input-wrapper">
                        <input type="email" name="email" value="<?php echo isset($email_usuario) ? htmlspecialchars($email_usuario) : ''; ?>" required>
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
</body>
</html>