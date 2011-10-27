<?php

if (!isset($_GET['file']) || $_GET['file'] == "") exit('nofile');
$targetFile = $_SERVER['DOCUMENT_ROOT'] . $_GET['file'];
if (!file_exists($targetFile)) exit('gonefile');
$img = imagecreatefrompng($targetFile);
if ($img === false) exit('nopng');
if (imagesx($img) != imagesy($img)) exit('notsquare');
if (imagesx($img) < 256) exit('toosmall');
if (imagesx($img) > 256) {
	// HD texture; resize to normal
	$tmp = imagecreatetruecolor(256,256);
	imagealphablending($tmp, false);
	imagesavealpha($tmp, true);
	imagecopyresized($tmp,$img, 0,0, 0,0, 256,256, imagesx($img),imagesy($img));
	imagedestroy($img);
	$img = $tmp;
}
$x = 9*16; // Location of the glowstone block texture
$y = 6*16;
$glow = imagecreatetruecolor(16,16);
imagecopy($glow,$img, 0,0, $x,$y, 16,16);

// Save the clipping
$newFile = tempnam('../img/tmp', 'glow_');
imagepng($glow, $newFile, 9, PNG_FILTER_PAETH);
chmod($newFile, 0664);

imagedestroy($glow);
unlink($targetFile); // Don't need that file any more
exit(str_replace($_SERVER['DOCUMENT_ROOT'],'',$newFile));