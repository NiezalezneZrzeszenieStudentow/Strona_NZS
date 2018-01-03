<?php
	require_once('SimpleImage.php');
	
	$src = $_GET['w'];
	$width = $_GET['w'];
	$height = $_GET['h'];
	
	if(empty($src) || $width*$height == 0){
		die('Błędne parametry');	
	}
	
	$Image = new SimpleImage();
	$Image->load($src);
	$image->resize($width, $height);
	
	header('Content-type: '.$Image->mime);
	$Image->output();
?>