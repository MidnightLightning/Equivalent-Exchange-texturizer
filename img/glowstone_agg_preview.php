<?php
// Get glowstone block texture image from query string and overlay interface over it

require_once('../inc/stacked_item.php');
if (!isset($_GET['file']) || $_GET['file'] == '') fail_image('No glowstone file given');
$glow = $_SERVER['DOCUMENT_ROOT'] . $_GET['file'];
if (!file_exists($glow)) fail_image('No such glowstone file');
$glow = @imagecreatefrompng($glow);
if ($glow === false) fail_image('Invalid glowstone file');

$type = (isset($_GET['t']) && $_GET['t'] != "")? $_GET['t'] : 'default';
$top_file = 'glow_types/'.$type.'_top.png';
$front_file = 'glow_types/'.$type.'_front.png';
if (!file_exists($top_file) || !file_exists($front_file)) fail_image('Invalid overlay type');
$overlay_top = imagecreatefrompng($top_file);
$overlay_front = imagecreatefrompng($front_file);

$preview = imagecreatetruecolor(16,32);
imagealphablending($preview, false);
imagesavealpha($preview, true);

// Create top
$top = new stackedItem();
$top->addLayer($glow);
$top->addLayer($overlay_top);
$img = $top->getImage();
imagecopy($preview,$img, 0,0, 0,0, 16,16);

// Create front
$front = new stackedItem();
$front->addLayer($glow);
$front->addLayer($overlay_front);
$img = $front->getImage();
imagecopy($preview,$img, 0,16, 0,0, 16,16);

// Resize for previewing
$final = imagecreatetruecolor(32,64);
imagealphablending($final, false);
imagesavealpha($final, true);
imagecopyresized($final,$preview, 0,0, 0,0, 32,64, 16,32);

header('Content-Type: image/png');
imagepng($final);

imagedestroy($overlay_top);
imagedestroy($overlay_front);
imagedestroy($glow);
imagedestroy($preview);

function fail_image($msg) {
	$img = imagecreatetruecolor(200, 25);
	$c = imagecolorallocate($img, 255,200,200);
	imagefill($img, 0,0, $c);
	$c = imagecolorallocate($img, 255,0,0);
	imagestring($img, 2,5,5, $msg, $c);
	header('Content-Type: image/png');
	imagepng($img);
	imagedestroy($img);
	exit;
}
