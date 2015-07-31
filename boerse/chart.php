<?php
//
// daxchart.php - Tageschart wird geladen und ein bißchen zurechtgeschnitten.
//

$symbol = "^GDAXI";
$src = "http://chart.finance.yahoo.com/z?s=" . urlencode($symbol) . "&t=1d&q=l&l=on&z=l&a=v&p=s&lang=de-DE&region=DE&random=" . rand(1,9999999999999999999);

$daxChart = @imagecreatefrompng($src); 	
if ($daxChart) {
	$daxCropped = imagecreatetruecolor(800, 330); 
	imagecopyresampled($daxCropped, $daxChart, 0, 0, 0, 0, 800, 330, 800, 330); 
	header('Content-type: image/png');
	imagejpeg($daxCropped);
	imagedestroy($daxCropped); 
}

?>