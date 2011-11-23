<?php
// create the dust type CSS sprite

require_once('../inc/colored_item.php');
$color = "00AA00";

$types = array('dust', 'ball', 'vial', 'vial2', 'paper', 'bowl', 'jar', 'can');
$sprite = imagecreatetruecolor(16,count($types)*16);
imagealphablending($sprite, false);
imagesavealpha($sprite, true);

foreach($types as $i => $type) {
	$mask_file = 'dust_types/'.$type.'_mask.png';
	$base_file = 'dust_types/'.$type.'_base.png';
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
	$mask = (file_exists($mask_file))? imagecreatefrompng($mask_file) : false;
	$base = (file_exists($base_file))? imagecreatefrompng($base_file) : false;

	$d = new coloredItem();
	if ($mask) $d->setMask($mask);
	if ($base) $d->setBase($base);
	$d->setColor($color);
	$img = $d->getImage();
	imagecopy($sprite,$img, 0,$i*16, 0,0, 16,16);
	imagedestroy($img);
}

header('Content-Type: image/png');
imagepng($sprite, null, 9);

if ($mask) imagedestroy($mask);
if ($base) imagedestroy($base);
imagedestroy($sprite);
