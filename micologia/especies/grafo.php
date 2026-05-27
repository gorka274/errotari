<?php
error_reporting(0); // Evita que Warnings corrompan el PNG
// 1. OBTENER Y SANITIZAR EL ID (Evita inyecciones SQL)
$ID = intval($_SERVER['QUERY_STRING']);

if ($ID === 0) {
    exit; // Si no hay un ID válido, cancelamos la generación de la imagen
}

// 2. CREDENCIALES DINÁMICAS (Local vs Servidor real)
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    $user = "root";
    $password = "";
    $base = "bdmicol";
} else {
    $user = "myerrotari";
    $password = "R1hrkd0a";
    $base = "bdmicol";
}

$conexion = mysqli_connect("localhost", $user, $password, $base);
if (!$conexion) {
    exit;
}

$tabla = "cronologia";
$resultado = mysqli_query($conexion, "SELECT * FROM $tabla WHERE idesp = $ID");
$num = mysqli_num_rows($resultado);

if ($num) {
    // Carga la plantilla de fondo (Asegúrate de que "histo.png" esté en esta misma carpeta)
    $grafo = imagecreatefrompng(__DIR__ . "/histo.png");

    // Asignación de colores para las barras (Coincidiendo con la leyenda CSS)
    $color1 = imagecolorallocate($grafo, 110, 187, 255); // c-rara (Azul: #6ebbff)
    $color2 = imagecolorallocate($grafo, 255, 210, 76);  // c-comun (Amarillo: #ffd24c)
    $color3 = imagecolorallocate($grafo, 255, 145, 77);  // c-bastante (Naranja: #ff914d)
    $color4 = imagecolorallocate($grafo, 255, 87, 87);   // c-abundante (Rojo: #ff5757)

    // Forzamos a leer el resultado de forma numérica estricta (0, 1, 2...)
    // El índice 0 suele ser el 'idesp', y del 1 al 48 son las 4 semanas de cada uno de los 12 meses
    $salida = mysqli_fetch_array($resultado, MYSQLI_NUM);

    for ($i = 1; $i <= 48; $i++) {
        if (isset($salida[$i]) && $salida[$i]) {
            $valor = $salida[$i];
            $color = null;

            switch ($valor) {
                case 1:
                    $color = $color1;
                    break;
                case 2:
                    $color = $color2;
                    break;
                case 3:
                    $color = $color3;
                    break;
                case 4:
                    $color = $color4;
                    break;
            }

            // Si el valor tiene un color asignado, PHP "dibuja" el rectángulo sobre el fondo
            if ($color !== null) {
                imagefilledrectangle($grafo, 16 + $i * 11, 60 - 10 * $valor, 24 + $i * 11, 60, $color);
            }
        }
    }

    // Enviamos las cabeceras para decirle al navegador que esto no es texto, sino una imagen PNG
    if (ob_get_length()) ob_clean(); // Limpia cualquier espacio en blanco o Notice previo
    header("Content-type: image/png");
    imagepng($grafo);

    // Ya no necesitas llamar a imagedestroy($grafo); PHP limpia la memoria solo al llegar aquí.
}

mysqli_close($conexion);
?>