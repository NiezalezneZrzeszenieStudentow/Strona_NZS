<?php

/*
 *Plugin Name: Email chats
 *Plugin URI:http://www.nzs.put.poznan.pl
 *Description: Emaile do pliku.
 *Version: 1.0
 *Author: Karol Znojkiewicz
 *Author URI: http://www.karolznojkiewicz.pl
 *License: GPL2
 */
 
if ( !function_exists( 'wp_mail' ) ) :

function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
	
	$date = date('Y-m-d H:i:s');
	
	$email_log = "## Email ({$date}) ##\nTo: {$to}\nSubject: {$subject}\nMessage: {$message}\n## ## ## ## ## ##\n";
	
	$filename = __DIR__.DIRECTORY_SEPARATOR.'emails.log';
	
	file_put_contents($filename, $email_log, FILE_APPEND);
	/*zapisywanie do pliku(gdzie, co, każda na koniec linii)*/
}
endif;
 
?>