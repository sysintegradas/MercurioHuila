<?php
session_start();
$_SESSION['tmptxt'] = randomText(5);
function randomText($length){
	$key="";
		for($i=0;$i<$length;$i++){
			$key.= rand(0,9);
}
return $key;
}
header('Content-Type: image/png');
$im = imagecreatetruecolor(400, 30);
$blanco = imagecolorallocate($im, 255, 255, 255);
$negro = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 399, 29, $blanco);
$fuente = './Logisoso.ttf';
$texto = $_SESSION['tmptxt'];
imagettftext($im, 25, 0, 155, 25, $negro, $fuente, $texto);
imagepng($im);
?>

