<?php

// Create an icon/block texture from multiple stacked layers
class stackedItem {
	private $_layers = array();
	
	function addLayer($im) {
		if (!is_resource($im) || get_resource_type($im) != 'gd') return false;
		$this->_layers[] = $im;
	}
	
	function getImage() {
		if (count($this->_layers) == 0) return false;
		if (count($this->_layers) == 1) return $this->_layers[0]; // If only one layer, nothing to merge
		$img = imagecreatetruecolor(16,16);
		imagealphablending($img, false);
		imagesavealpha($img, true);
		$t = imagecolorallocatealpha($img, 0,0,0,127);
		imagefill($img, 1,1, $t); // Make transparent
		
		foreach($this->_layers as $i => $layer) {
			imagecopy($img,$layer, 0,0, 0,0, 16,16);
			if ($i == 0) {
				// After first layer, switch to transparent overlay mode
				imagealphablending($img, true);
			}
		}
		return $img;
	}
}