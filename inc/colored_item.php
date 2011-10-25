<?php

// Create a new item icon with various colors, returning the image resource
class coloredItem {
	private $_mask;
	private $_base;
	private $_color;
	
	function setMask($im) {
		if (get_resource_type($im) != 'gd') return false;
		$this->_mask = $im;
	}
	function setBase($im) {
		if (get_resource_type($im) != 'gd') return false;
		$this->_base = $im;
	}
	function setColor($color) {
		$this->_color = $color;
	}
	
	function getImage() {
		$mask = $this->_mask;
		if (!is_resource($mask) || get_resource_type($mask) != 'gd' || strlen($this->_color) != 6) return false;
		
		$new = imagecreatetruecolor(16,16);
		imagealphablending($new, false); // Use the paint transparency as the final transparency
		imagesavealpha($new, true);
		$t = imagecolorallocatealpha($new, 0,0,0,127);
		imagefill($new, 1,1, $t); // Make transparent

		$sr = hexdec(substr($this->_color,0,2)); // Base color to tint the dust
		$sg = hexdec(substr($this->_color,2,2));
		$sb = hexdec(substr($this->_color,4,2));

		$shift_factor = 0.9;
		$colors = array();
		for ($x=0; $x<16; $x++) {
			for ($y=0; $y<16; $y++) {
				$p = imagecolorat($mask, $x, $y);
				$p = imagecolorsforindex($mask, $p);
				$delta = ($p['red']-128)*$shift_factor; // Grayscale shift; neutral gray is no color shift.
				$nr = $this->normalize_color($sr + $delta);
				$ng = $this->normalize_color($sg + $delta);
				$nb = $this->normalize_color($sb + $delta);
				$label = dechex($nr).dechex($ng).dechex($nb).dechex($p['alpha']);
				if (!array_key_exists($label, $colors)) $colors[$label] = imagecolorallocatealpha($new, $nr, $ng, $nb, $p['alpha']);
				$c = $colors[$label];
				imagesetpixel($new, $x,$y, $c);
			}
		}
		
		if (!is_resource($this->_base) || get_resource_type($this->_base) != 'gd') return $new; // No base image, just give the colored mask element
		
		// Apply color over the top of the base
		$base = $this->_base;
		$final = imagecreatetruecolor(16,16);
		imagealphablending($final, false);
		imagesavealpha($final, true);
		
		imagecopy($final,$base, 0,0, 0,0, 16,16); // Copy base image literally
		imagealphablending($final, true);
		imagecopy($final,$new, 0,0, 0,0, 16,16); // Apply colored layer transparently

		imagedestroy($new);
		return $final;		
	}

	function normalize_color($n) {
		$n = intval($n);
		if ($n > 255) $n = 255;
		if ($n < 0) $n = 0;
		return $n;
	}
}