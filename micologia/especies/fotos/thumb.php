<?php
$ID= $_SERVER['QUERY_STRING'];
      if (!$grafo = imagecreatefromjpeg($ID)) {
        echo "Error al abrir imagen!";exit;
      }
	list($ancho,$alto)=getimagesize($ID);
    	$pr = $alto / 90;
	$x = $ancho / $pr; 
    	$y = 90;

	$thumb = imagecreatetruecolor($x, $y);
	imagecopyresampled ($thumb,$grafo,0,0,0,0,$x,$y,imagesx($grafo),imagesy($grafo));
	header("Content-type: image/jpeg");
	imagejpeg($thumb,null,85);
	imagedestroy($grafo);
	imagedestroy($thumb);
?> 