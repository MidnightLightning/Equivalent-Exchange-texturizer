<?php
// create the collector base CSS sprite

$type_folder = 'collector_bases/';
$dh = opendir($type_folder);
$types = array();
while(($file = readdir($dh)) !== false) {
	if (pathinfo($type_folder.$file, PATHINFO_EXTENSION) == 'png') {
		$types[] = $file;
	}
}

$sprite = imagecreatetruecolor(16,count($types)*16);
imagealphablending($sprite, false);
imagesavealpha($sprite, true);

foreach($types as $i => $type) {
	$img = imagecreatefrompng($type_folder.$type);
	imagecopy($sprite,$img, 0,$i*16, 0,0, 16,16);
	imagedestroy($img);
}

header('Content-Type: image/png');
imagepng($sprite, null, 9);

if ($img) imagedestroy($img);
imagedestroy($sprite);
