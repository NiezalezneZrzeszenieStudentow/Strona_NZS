<?php

/*
 *Plugin Name: NZS Cytaty
 *Plugin URI:http://www.nzs.put.poznan.pl
 *Description: Wtyczka wyświetlająca losowe cytaty.
 *Version: 1.0
 *Author: Karol Znojkiewicz
 *Author URI: http://www.karolznojkiewicz.pl
 *License: GPL2
 */
 
 
 function edu_cytaty_get_random_quote(){
		$quote = array(
		'To, że milczę, nie znaczy, że nie mam nic do powiedzenia.',
		'Lepiej zaliczać się do niektórych, niż do wszystkich.',
		'Czytanie książek to najpiękniejsza zabawa, jaką sobie ludzkość wymyśliła'
		
	); 
	return $quote[mt_rand(0, count($quote)-1)];
 }
 function edu_cytaty_random_quote(){
		$quote = '"'.edu_cytaty_get_random_quote().'"';
		_log($quote);
		echo '<p>'.$quote.'</p>'; 
 }
?>