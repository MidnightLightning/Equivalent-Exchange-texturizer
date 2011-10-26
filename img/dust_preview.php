<?php
// Get color from query string, and make 3 dust images from it

require_once('inc/colored_item.php');

$colors = explode(',', $_GET['c']);
$trio = imagecreatetruecolor(count($colors)*16, 16);
imagealphablending($trio, false);
imagesavealpha($trio, true);
$type = 'vial2';
$mask = imagecreatefrompng('img/types/'.$type.'_mask.png');
$base = imagecreatefrompng('img/types/'.$type.'_base.png');

foreach($colors as $i => $color) {
	$d = new coloredItem();
	$d->setMask($mask);
	$d->setBase($base);
	$d->setColor($color);
	$img = $d->getImage();
	imagecopy($trio,$img, $i*16,0, 0,0, 16,16);
	imagedestroy($img);
}

// Resize for final showing
$final = imagecreatetruecolor(count($colors)*32,32);
imagealphablending($final, false);
imagesavealpha($final, true);
imagecopyresized($final,$trio, 0,0, 0,0, count($colors)*32,32, count($colors)*16,16);

header('Content-Type: image/png');
imagepng($final);

imagedestroy($mask);
imagedestroy($trio);
imagedestroy($final);