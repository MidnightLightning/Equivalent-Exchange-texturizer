<?php
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

/* Customized to extract the terrain.png file of any ZIP file uploaded */
if (!empty($_FILES)) {
	if ($_FILES['Filedata']['error'] == 1) exit("false");
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$targetPath = str_replace('//', '/', $targetPath);

	
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (strtolower($fileParts['extension']) == 'zip') {
		// Attempt to grab the terrain.png file out of the zip
		$z = new ZipArchive;
		if ($z->open($tempFile) === true) {
			$t = $z->getFromName('terrain.png');
			if ($t !== false) {
				$targetFile = tempnam($targetPath, 'terrain_');
				file_put_contents($targetFile, $t);
				chmod($targetFile, 0664);
				echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
				exit;
			}
		}
	} else {
		// Assume this is the terrain.png file
		$targetFile =  $targetPath . $_FILES['Filedata']['name'];
		move_uploaded_file($tempFile,$targetFile);
		echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
		exit;
	}
}
?>