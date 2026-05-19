<?php
// 1. Iniciamos la sesión para poder acceder a ella
session_start();

// 2. Vaciamos todas las variables de sesión
$_SESSION = array();

// 3. Borramos la cookie de sesión del navegador (altamente recomendado por seguridad)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Destruimos por completo la sesión en el servidor
session_destroy();

// 5. Redirigimos al dueño de vuelta a la pantalla de login secreta
header("Location: index.php");
exit;
?>