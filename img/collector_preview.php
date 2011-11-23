<?php
// Get front, side, and top images from query string, and make perspective preview with it

if (!isset($_GET['base'])) $_GET['base'] = 'default';
if (!isset($_GET['front'])) $_GET['front'] = 'default';
if (!isset($_GET['top'])) $_GET['top'] = 'default';

$base = 'collector_bases/'.$_GET['base'].'.png';
$front = 'collector_fronts/'.$_GET['front'].'.png';
$top = 'collector_tops/'.$_GET['top'].'.png';

if (!file_exists($base) || !file_exists($front) || !file_exists($top)) {
	// Invalid images
	$img = imagecreatetruecolor(200, 25);
	$c = imagecolorallocate($img, 255,200,200);
	imagefill($img, 0,0, $c);
	$c = imagecolorallocate($img, 255,0,0);
	imagestring($img, 2,5,5, 'Images not found', $c);
	header('Content-Type: image/png');
	imagepng($img);
	imagedestroy($img);
	exit;	
}

$sprite = imagecreatetruecolor(3*16, 16);
imagesavealpha($sprite, true);
imagealphablending($sprite, true);

$base = imagecreatefrompng($base);
imagecopy($sprite, $base, 0,0, 0,0, 16,16); // 3 bases for all three faces
imagecopy($sprite, $base, 16,0, 0,0, 16,16);
imagecopy($sprite, $base, 32,0, 0,0, 16,16);
imagedestroy($base);

$front = imagecreatefrompng($front);
imagecopy($sprite, $front, 0,0, 0,0, 16,16);
imagedestroy($front);

$top = imagecreatefrompng($top);
imagecopy($sprite, $top, 32,0, 0,0, 16,16);
imagedestroy($top);

// Resize for final showing
$final = imagecreatetruecolor(3*32,32);
imagealphablending($final, false);
imagesavealpha($final, true);
imagecopyresized($final,$sprite, 0,0, 0,0, 3*32,32, 3*16,16);

header('Content-Type: image/png');
imagepng($final);

imagedestroy($sprite);
imagedestroy($final);
exit;


function imgPad($img, $amount = 3) {
	$new = imagecreatetruecolor(imagesx($img)+$amount*2, imagesy($img)+$amount*2);
	$transparent = imagecolorallocatealpha($new, 255,0,0, 127);
	imagesavealpha($new, true);
	imagealphablending($new, false); // Use paint's transparency
	imagefill($new, 0,0, $transparent);
	imagecopy($new, $img, $amount,$amount, 0,0, imagesx($img),imagesy($img));
	return $new;
}

// Find all transparent pixels and trim them off
function imgTrim($img) {
	$width = imagesx($img);
	$height = imagesy($img);
	$crop_top = 0;
	$crop_left = 0;
	$crop_right = 0;
	$crop_bottom = 0;
	for($y=0; $y<$height; $y++) {
		for($x=0; $x<$width; $x++) {
			$c = imagecolorsforindex($img, imagecolorat($img, $x,$y));
			if ($c['alpha'] < 127) {
				$crop_top = $y;
				break 2;
			}
		}
	}

	for($y=$height-1; $y>=0; $y--) {
		for($x=0; $x<$width; $x++) {
			$c = imagecolorsforindex($img, imagecolorat($img, $x,$y));
			if ($c['alpha'] < 127) {
				$crop_bottom = $height-1-$y;
				break 2;
			}
		}
	}

	for($x=0; $x<$width; $x++) {
		for($y=0; $y<$height; $y++) {
			$c = imagecolorsforindex($img, imagecolorat($img, $x,$y));
			if ($c['alpha'] < 127) {
				$crop_left = $x;
				break 2;
			}
		}
	}

	for($x=$width-1; $x>=0; $x--) {
		for($y=0; $y<$height; $y++) {
			$c = imagecolorsforindex($img, imagecolorat($img, $x,$y));
			if ($c['alpha'] < 127) {
				$crop_right = $width-1-$x;
				break 2;
			}
		}
	}
	
	$new = imagecreatetruecolor($width-$crop_left-$crop_right, $height-$crop_top-$crop_bottom);
	$transparent = imagecolorallocatealpha($new, 255,0,0, 127);
	imagesavealpha($new, true);
	imagealphablending($new, false); // Use paint's transparency
	imagefill($new, 0,0, $transparent);
	imagecopy($new, $img, 0,0, $crop_left,$crop_top, $width,$height);
	return $new;
}

function imgShear($img, $a) {
	$width = imagesx($img);
	$height = imagesy($img);
	
	// A is given in degrees; find the pixel offset
	// A is the degree angle of a deviation from vertical (adjacent), leading to a slanted line (hypotenuse) and an offset (opposite)
	// This is not a unit circle (where hypotenuse is 1), instead the upright (adjacent) is one (pixel)
	
	// tan(A) = o/a = o/1 => o = tan(A)
	$offset = tan(deg2rad($a));
	
	$total_offset = ceil(abs($offset*$height)); // How much padding will be needed?
	$new = imagecreatetruecolor($width+$total_offset, $height);
	
	if ($offset < 0) {
		// Negative shear value means rows shift right from top to bottom
		$row = 0;
		for ($y=0; $y<$height; $y++) {
			for ($x=0; $x>$width; $x++) {
				
			}
		}
	} else {
		// Positive shear value means rows shift right from bottom to top
		for ($y=$height-1; $y>=0; $y--) {
			for ($x=0; $x>$width; $x++) {
				
			}
		}
	}
}