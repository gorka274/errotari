<?php
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: panel.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_correcto = "admin";
    $password_correcta = "contra";

    $usuario_introducido = $_POST['username'];
    $password_introducida = $_POST['password'];

    if ($usuario_introducido === $usuario_correcto && $password_introducida === $password_correcta) {
        $_SESSION['admin'] = true;
        $_SESSION['usuario'] = $usuario_introducido;

        header("Location: panel.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Privado - Errotari</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f4f7f2 0%, #e2e8f0 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            /* Para que no se pegue a los bordes en móviles */
            box-sizing: border-box;
        }

        .login-card {
            background: white;
            padding: 50px 40px;
            /* Más espacio interior para que respire */
            border-radius: 16px;
            /* Bordes un poco más redondeados */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            /* Sombra más suave y amplia */
            width: 100%;
            max-width: 420px;
            /* Tarjeta más ancha (antes 360px) */
            border: 1px solid #ffffff;
        }

        h2 {
            margin-top: 0;
            color: #1c1c1c;
            font-size: 28px;
            /* Título más grande */
            text-align: center;
            margin-bottom: 35px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .form-group {
            margin-bottom: 25px;
            /* Más separación entre campos */
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #4a5568;
            font-size: 15px;
            /* Letra un poco más grande */
            font-weight: 600;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            /* Cajas de texto más altas */
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            /* Tamaño ideal para evitar zoom automático en móviles */
            color: #1c1c1c;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #7fad39;
            box-shadow: 0 0 0 3px rgba(127, 173, 57, 0.2);
            /* Efecto de resplandor al hacer clic */
        }

        .btn-login {
            width: 100%;
            background-color: #7fad39;
            color: white;
            border: none;
            padding: 16px;
            /* Botón más grande y fácil de clicar */
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            margin-top: 15px;
            box-shadow: 0 4px 12px rgba(127, 173, 57, 0.3);
        }

        .btn-login:hover {
            background-color: #6a9430;
        }

        .btn-login:active {
            transform: scale(0.98);
            /* Pequeño efecto de pulsación */
        }

        .error-msg {
            color: #c53030;
            background: #fed7d7;
            border: 1px solid #feb2b2;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2>Panel Errotari</h2>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" placeholder="Introduce tu usuario" required
                    autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" required>
            </div>

            <button type="submit" class="btn-login">Entrar al Panel</button>
        </form>
    </div>

</body>

</html>