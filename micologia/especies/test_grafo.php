<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    die("DB error: " . mysqli_connect_error());
}

$tabla = "cronologia";
$resultado = mysqli_query($conexion, "SELECT * FROM $tabla WHERE idesp = 1001");
$num = mysqli_num_rows($resultado);

echo "Num rows: $num\n";

if ($num) {
    $salida = mysqli_fetch_array($resultado, MYSQLI_NUM);
    echo "Columns: " . count($salida) . "\n";
    print_r($salida);
    
    $png_path = __DIR__ . "/histo.png";
    echo "PNG Path: $png_path (exists? " . (file_exists($png_path) ? "yes" : "no") . ")\n";
    
    $grafo = @imagecreatefrompng($png_path);
    if (!$grafo) {
        $error = error_get_last();
        echo "Error loading PNG: " . print_r($error, true) . "\n";
    } else {
        echo "PNG loaded successfully.\n";
    }
}
