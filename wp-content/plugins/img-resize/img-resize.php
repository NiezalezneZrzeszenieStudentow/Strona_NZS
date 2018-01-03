<?php

/*
 *Plugin Name: Email img-resize
 *Plugin URI:http://www.nzs.put.poznan.pl
 *Description: Zmiana wielkości obrazka.
 *Version: 1.0
 *Author: Karol Znojkiewicz
 *Author URI: http://www.karolznojkiewicz.pl
 *License: GPL2
 */

function img_resize($args, $content){
	
	$args = shortcode_atts(array('width=>150', 'height=>100'), $args);
	
	$w = 150;
	$h = 100;
	
	$script_url = plugins_url('/image.php', __FILE__);
	//$script_url .= '?s='.$content.'&w='.$w.'&h='.$h;
	
	$script_url = add_query_arg(array(
		's'=>$content,
		'w'=>$args['width'],
		'h'=>$args['height']
	),$script_url);
	/*Przypisywanie do url parametrów*/
	return '<img src="$script_url" alt="" >';	
}
add_shortcode('img-res', 'img_resize');
 
?>