<?php
// Get color from query string, and make 3 dust images from it

require_once('../inc/colored_item.php');

$colors = explode(',', $_GET['c']);
$type = (isset($_GET['t']) && $_GET['t'] != '')? $_GET['t'] : 'dust';
$mask_file = 'types/'.$type.'_mask.png';
$base_file = 'types/'.$type.'_base.png';
if (!file_exists($mask_file) && !file_exists($base_file)) {
	// Invalid type
	$img = imagecreatetruecolor(200, 25);
	$c = imagecolorallocate($img, 255,200,200);
	imagefill($img, 0,0, $c);
	$c = imagecolorallocate($img, 255,0,0);
	imagestring($img, 2,5,5, 'Invalid type passed', $c);
	header('Content-Type: image/png');
	imagepng($img);
	imagedestroy($img);
	exit;
}

$trio = imagecreatetruecolor(count($colors)*16, 16);
imagealphablending($trio, false);
imagesavealpha($trio, true);

$mask = (file_exists($mask_file))? imagecreatefrompng($mask_file) : false;
$base = (file_exists($base_file))? imagecreatefrompng($base_file) : false;

foreach($colors as $i => $color) {
	$d = new coloredItem();
	if ($mask) $d->setMask($mask);
	if ($base) $d->setBase($base);
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