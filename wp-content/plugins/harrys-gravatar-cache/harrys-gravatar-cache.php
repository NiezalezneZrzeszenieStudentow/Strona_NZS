<?php
/*
/**
 * Plugin Name: Harrys Gravatar Cache
 * Plugin URI: https://www.all4hardware4u.de
 * Description: Beschleunigt die Website durch simples und effektives Caching von Gravataren (Globally Recognized Avatars), damit diese vom eigenenem Webserver ausgeliefert werden und nicht vom Gravatar-Server nachgeladen werden müssen.
 * Version: 1.7.2
 * Author: Harry Milatz
 * Author URI: https://www.all4hardware4u.de
 * Text Domain: harrys-gravatar-cache
 * Domain Path: /languages
 * License: GPL3

Copyright 2015-2017 Harry Milatz (email : harry@all4hardware4u.de)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/
if (!defined('ABSPATH')) exit; // Verlassen bei direktem Zugriff
require_once( ABSPATH . "wp-includes/pluggable.php" );
// Datenbanktabelle
global $wpdb;
$table=$wpdb->prefix.'harrys_gravatar_cache';
// Pfade festlegen
$path=wp_upload_dir();
$path=$path['basedir']."/gravatar-cache/";
// Load translations
load_plugin_textdomain( 'harrys-gravatar-cache', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

/* Install */
$plugin = plugin_basename(__FILE__);
if( is_admin() || current_user_can('manage_options') ) {
	add_action( 'admin_menu', 'harry_add_pages' );
	add_action( 'admin_init', 'save_settings');
	add_filter("plugin_action_links_$plugin", 'harrys_plugin_settings_link' );
}
if (is_multisite() ) {
	add_action( 'network_admin_menu', 'harry_add_pages' );
	add_action( 'admin_init', 'save_settings');
}

/* Einstellungsseite den Einstellungen hinzufügen */
function harry_add_pages() {
	add_options_page(
	__( 'Harrys Gravatar Cache Settings', 'harrys-gravatar-cache' ),
	__( 'Harrys Gravatar Cache Settings', 'harrys-gravatar-cache' ),
	'manage_options',
	'harrys-gravatar-cache-options',
	'Einstellungen'
	);
}
/* Link zu Einstellungen auf der Pluginseite */
function harrys_plugin_settings_link($links) {
	$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=harrys-gravatar-cache-options' ) ) . '">' . __( 'Settings', 'harrys-gravatar-cache' ) . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}

// bei Aktivierung des Plugins - für die einzelnen Installationen bei Multi bzw einmal bei Single
function harrys_gravatar_cache_activation() {
	global $wpdb;
	$table=$wpdb->prefix.'harrys_gravatar_cache';
	$path=wp_upload_dir();
	$path=$path['basedir']."/gravatar-cache/";
	make_folder($path);
	get_size_gravatar_hgc($table, $path);
	get_copy_options($table, $path);
	is_writeable_proof($table, $path);
	$cache_time=40320;
	$wpdb->update($table, array('cache_time' => $cache_time), array('id' => 1), array('%d'));
}
// bei Aktivierung des Plugins - erster Aufruf
function harrys_gravatar_cache_installation($networkwide) {
	global $wpdb;  
	$hg_cache_find = $wpdb->prefix.'harrys-gravatar-cache';
	// Prüfen ob eine Seite schon vorhanden ist und das Plugin bereits installiert
	if($wpdb->get_var("SHOW TABLES LIKE '$hg_cache_find'") != $hg_cache_find) {
	// Prüfen ob es sich um eine Multisite / Netzwerk Installation handelt
		if (is_multisite() && $networkwide) {
		// Multisite / Netzwerk Plugin Installation
			// Aktuellen Blog zwischenspeichern
			$current_blog = $wpdb->blogid;
			// Array fuer alle aktiven Blogs/der aktiven Website
			$activated = array();
			// Durch alle Blogs/Wbesites gehen und das PlugIn aktivieren
			$sql = "SELECT blog_id FROM $wpdb->blogs";
			$blogids = $wpdb->get_co1($wpdb->prepare($sql));
			// Jeder Webseite die Tabellen in der Datenbank einfügen und Aktivierung im Array speichern
			foreach ($blogids as $blogid) {
				switch_to_blog($blogid);
				harrys_gravatar_cache_activation();
				$activated[] = $blogid;
			}
			switch_to_blog($current_blog);
			$plugins = FALSE;
			$plugins = get_site_option('active_plugins');
			if ( $plugins ) {
			// Plugin aktivieren
				$pugins_to_active = array(
					'harrys-gravatar-cache/harrys-gravatar-cache.php'
				);
				foreach ( $pugins_to_active as $plugin ) {
					if ( ! in_array( $plugin, $plugins ) ) {
						array_push( $plugins, $plugin );
						update_site_option( 'active_plugins', $plugins );
					}
				}
			}
		// Normale Plugin Installation / einzelne Website
		} else {
			harrys_gravatar_cache_activation();
		}
	}
}
register_activation_hook( __FILE__, 'harrys_gravatar_cache_installation' );

// Wenn eine Website hinzugefügt wird
function add_blog($blog_id) {
	if ( is_plugin_active_for_network( 'harrys-gravatar-cache/harrys-gravatar-cache.php' ) ) {
		switch_to_blog($blog_id);
		// Neuer Webseite die Tabelle in der Datenbank einfügen
		harrys_gravatar_cache_activation();
		restore_current_blog();
	}
}
add_action ( 'wpmu_new_blog', 'add_blog', 99 );

// Wenn eine Website gelöscht wird
function delete_blog($tables) {
	global $wpdb;
	// Tabellen die gelöscht werden sollen
	$tables[] = $wpdb->prefix.'harrys_gravatar_cache';
	return $tables;
}
add_filter ( 'wpmu_drop_tables', 'delete_blog', 99 );

// Uninstall
function harrys_gravatar_cache_uninstall() {
	global $wpdb;
	$table=$wpdb->prefix.'harrys_gravatar_cache';
	$wpdb->query( "DROP TABLE `{$table}` " );
	$path=wp_upload_dir();
	$path=$path['basedir']."/gravatar-cache/";
	empty_cache($path);
	@rmdir("$path");
}

function harrys_gravatar_cache_plugin_uninstall() {
	global $wpdb;  
	// Prüfen ob es sich um eine Multisite / Netzwerk Deinstallation handelt
	if (is_multisite() ) {
		// Multisite / Netzwerk Plugin deinstallation
		$blog = $wpdb->blogid;
		$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		// Von jeder Webseite die Tabelle in der Datenbank löschen
		foreach ($blogids as $blogid) {
			switch_to_blog($blogid);
			harrys_gravatar_cache_uninstall();
		}
	switch_to_blog($blog);
	// Normale Plugin deinstallation
	} else {
		harrys_gravatar_cache_uninstall();
	}
}
register_uninstall_hook( __FILE__, 'harrys_gravatar_cache_plugin_uninstall' );

/* Funktionen für die Einstellungsseite und Install */
// Cache Directory anlegen
function make_folder($path) {
	$path_ok=false;
	if (!is_dir($path))	{
		if (@mkdir("$path", 0755, true)) {
		$path_ok=1;
		}
		else {
		$path_ok=false;
		}
	}
	else {
	$path_ok=1;
	}
}
//Ordnerberechtigungen korrigieren
function correct_folder($path) {
	chmod($path,0755);
}
function correct_folder2($path) {
	chmod($path,0775);
}
// Dateien des Templates nach Avatargrösse durchsuchen
function get_size_gravatar_hgc($table, $path) {
	global $wp_filesystem;
	if (empty($wp_filesystem)) {
		require_once ( ABSPATH . "/wp-admin/includes/file.php" );
		WP_Filesystem();
	}
	$dateinamen=array();
	$template=get_template();
	if( $template=="CherryFramework" ) {
		if( $wp_filesystem->exists(get_template_directory().'/includes/theme-function.php') ) {$dateinamen[]=get_template_directory().'/includes/theme-function.php';}
		if( $wp_filesystem->exists(get_template_directory().'/loop/loop-author.php') ) {$dateinamen[]=get_template_directory().'/loop/loop-author.php';}
		if( $wp_filesystem->exists(get_template_directory().'/loop/loop-single.php') ) {$dateinamen[]=get_template_directory().'/loop/loop-single.php';}
		if( $wp_filesystem->exists(get_stylesheet_directory().'/includes/theme-function.php') ) {$dateinamen[]=get_stylesheet_directory().'/includes/theme-function.php';}
		if( $wp_filesystem->exists(get_stylesheet_directory().'/loop/loop-author.php') ) {$dateinamen[]=get_stylesheet_directory().'/loop/loop-author.php';}
		if( $wp_filesystem->exists(get_stylesheet_directory().'/loop/loop-single.php') ) {$dateinamen[]=get_stylesheet_directory().'/loop/loop-single.php';}
	} else {
		if( $wp_filesystem->exists(get_template_directory().'/functions.php') ) {$dateinamen[]=get_template_directory().'/functions.php';}
		if( $wp_filesystem->exists(get_template_directory().'/lib/functions/template-comments.php') ) {$dateinamen[]=get_template_directory().'/lib/functions/template-comments.php';}
		if( $wp_filesystem->exists(get_template_directory().'/single.php') ) {$dateinamen[]=get_template_directory().'/single.php';}
		if( $wp_filesystem->exists(get_template_directory().'/comments.php') ) {$dateinamen[]=get_template_directory().'/comments.php';}
		if( $wp_filesystem->exists(get_template_directory().'/includes/functions/comments.php') ) {$dateinamen[]=get_template_directory().'/includes/functions/comments.php';}
		if( $wp_filesystem->exists(get_template_directory().'/includes/meta.php') ) {$dateinamen[]=get_template_directory().'/includes/meta.php';}
	if( $wp_filesystem->exists(get_stylesheet_directory().'/functions.php') ) {$dateinamen[]=get_stylesheet_directory().'/functions.php';}
	if( $wp_filesystem->exists(get_stylesheet_directory().'/lib/functions/template-comments.php') ) {$dateinamen[]=get_stylesheet_directory().'/lib/functions/template-comments.php';}
	if( $wp_filesystem->exists(get_stylesheet_directory().'/single.php') ) {$dateinamen[]=get_stylesheet_directory().'/single.php';}
	if( $wp_filesystem->exists(get_stylesheet_directory().'/comments.php') ) {$dateinamen[]=get_stylesheet_directory().'/comments.php';}
	if( $wp_filesystem->exists(get_stylesheet_directory().'/includes/functions/comments.php') ) {$dateinamen[]=get_stylesheet_directory().'/includes/functions/comments.php';}
	if( $wp_filesystem->exists(get_stylesheet_directory().'/includes/meta.php') ) {$dateinamen[]=get_stylesheet_directory().'/includes/meta.php';}
	}
	$count_datei=count($dateinamen);
	$active_theme_wp=get_option('template');
	if(!empty($count_datei)){
		foreach($dateinamen as $dateiname) {
			$fp=@fopen($dateiname,"r");
			if($fp) {
				$datei_inhalt=@fread($fp,filesize($dateiname));
				if($datei_inhalt) {
					preg_match('/avatar_size=(\d*)/',$datei_inhalt,$size);
					if(empty($size[1])) {
						preg_match("/'avatar_size' => (\d*)/",$datei_inhalt,$size);
					}
					if(empty($size[1])) {
						preg_match("/'avatar_size'=>(\d*)/",$datei_inhalt,$size);
					}
					if(empty($size[1])) {
						preg_match("/'avatar_size'=> (\d*)/",$datei_inhalt,$size);
					}
					if(empty($size[1])) {
						preg_match("/'avatar_size' =>(\d*)/",$datei_inhalt,$size);
					}
					if(empty($size[1])) {
						preg_match('/"avatar_size" => (\d*)/',$datei_inhalt,$size);
					}
					if(empty($size[1])) {
						preg_match('/"avatar_size"=>(\d*)/',$datei_inhalt,$size);
					}
					if(empty($size[1])) {
						preg_match('/"avatar_size"=> (\d*)/',$datei_inhalt,$size);
					}
					if(empty($size[1])) {
						preg_match('/"avatar_size" =>(\d*)/',$datei_inhalt,$size);
					}
					if(empty($size[1])) {
						preg_match('/get_avatar\(\D*?(\d*)\D*?\)/',$datei_inhalt,$size);
					}
					if(empty($size[1])) {
						preg_match('/get_avatar\(\D*?(\d{1,9})\D*?\)/',$datei_inhalt,$size);
					}
					if(empty($size)){
						if(!isset($size[1])) {
							$size[1]=null;
						}
						$size=$size[1];
					}
					if(!empty($size)) {
						$size=$size[1];
					}
					if(is_numeric($size)) {
						$avatar_size=$size;
						$size_get=1;
						@fclose($fp);
						break;
					}
				}
			}
			@fclose($fp);
		}
		if (!isset($avatar_size)) {
			$avatar_size=67;
			$size_get=2;
		}
	} else {
		$avatar_size=67;
		$size_get=2;
	}
	if (!is_numeric($avatar_size)) {
	$avatar_size=67;
	$size_get=2;
	}
	global $wpdb;
	$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$table}` ( `id` int(11) unsigned NOT NULL auto_increment, `size` int(11) NOT NULL, `size_get` int(11) NOT NULL, `get_option` int(11) NOT NULL, `cache_time` int(11) NOT NULL, `is_writeable` int(11) NOT NULL, `file_get_contents` int(11) NOT NULL, `fopen` int(11) NOT NULL, `curl` int(11) NOT NULL, `copy` int(11) NOT NULL, `active_theme` TEXT NOT NULL, PRIMARY KEY  (`id`) )");
	$usersTheme = $wpdb->get_row("SELECT * FROM $table");
	if(!isset($usersTheme->active_theme)){
		$wpdb->query ("ALTER TABLE `{$table}` ADD `active_theme` TEXT NOT NULL AFTER `copy`");
		$wpdb->update($table, array('active_theme' => $active_theme_wp), array('id' => 1), array('%s'));
	}
	if( !$wpdb->get_var($wpdb->prepare("SELECT size, size_get, get_option, cache_time, is_writeable, file_get_contents, fopen, curl, copy, active_theme FROM $table WHERE ID = %d", 1) ) ) {
		if(empty($avatar_size) || $avatar_size==0){$avatar_size=67;}
		if(empty($size_get) || $size_get==0){$size_get=2;}
		if(empty($get_option) || $get_option==0){$get_option=4;}
		if(empty($cache_time) || $cache_time==0){$cache_time=40320;}
		if(empty($file_get_contents)){$file_get_contents=0;}
		if(empty($fopen)){$fopen=0;}
		if(empty($curl)){$curl=0;}
		if(empty($copy) || $copy==0){$copy=1;}
		if(empty($is_writeable) || $is_writeable==0){$is_writeable=1;}
		if(empty($active_theme)){$active_theme=$active_theme_wp;}
		$wpdb->insert($table, array('size' => $avatar_size, 'size_get' => $size_get, 'get_option' => $get_option, 'cache_time' => $cache_time, 'is_writeable' => $is_writeable, 'file_get_contents' => $file_get_contents, 'fopen' => $fopen, 'curl' => $curl, 'copy' => $copy, 'active_theme' => $active_theme_wp), array('id' => 1), array('%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%s'));
		nothing_set($table, $path);
	}
	$wpdb->update($table, array('size' => $avatar_size, 'size_get' => $size_get, 'active_theme' => $active_theme_wp), array('id' => 1), array('%d', '%d', '%s'));
}
function nothing_set($table, $path) {
	get_size_gravatar_hgc($table, $path);
	get_copy_options($table, $path);
	is_writeable_proof($table, $path);
}
// Cache leeren
function empty_cache($path) {
	if ($dh = opendir($path)) {
		while (($file = readdir($dh)) !== false) {
			if ($file!="." AND $file !="..") {
			@unlink("$path$file");
			}
		}
		closedir($dh);
	}
}
// Kopieroption festlegen
function get_copy_options($table, $path) {
	global $wp_filesystem;
	if (empty($wp_filesystem)) {
		require_once ( ABSPATH . "/wp-admin/includes/file.php" );
		WP_Filesystem();
	}
	$get_option=0;
	$file_get_contents=0;
	$fopen=0;
	$curl=0;
	$copy=0;
	$url=plugin_dir_url('avatar-no.jpg');
	$testfile=$path."/test.png";
	//file_get_contents
	if (ini_get('allow_url_fopen') && function_exists('file_get_contents')) {
	$get_option=1;
	$file_get_contents=1;
	}
	//fopen
	if (ini_get('allow_url_fopen')) {
		if (false===$fh=wp_remote_fopen($url,false)) {
		$fopen=0;
		}
		else {
		$get_option=2;
		$fopen=1;
		}
	}
	//cURL
	if (function_exists('curl_init')) {
	$get_option=3;
	$curl=1;
	}
	//PHP Copy
	@copy($url, $testfile);
	if ($wp_filesystem->exists($testfile)) {
	$get_option=4;
	$copy=1;
	@unlink($testfile);
	}
	global $wpdb;
	$wpdb->update($table, array('get_option' => $get_option, 'file_get_contents' => $file_get_contents, 'fopen' => $fopen, 'curl' => $curl, 'copy' => $copy), array('id' => 1), array('%d'));
}
// Cacheordner beschreibbar ?
function is_writeable_proof($table, $path) {
	$is_writeable=0;
	if(is_writable($path)) {
	$is_writeable=1;
	}
	global $wpdb;
	$wpdb->update($table, array('is_writeable' => $is_writeable), array('id' => 1), array('%d'));
}

/* Einstellungsseite */
function Einstellungen() {
	if ( current_user_can( 'manage_options' ) ) { ?>
	<!-- Donation button -->
	<div id="donate" class="wrap">
		<div class="inside">
			<p><?php _e('If you like this plugin, please donate with PayPal or Amazon to support development and maintenance!', 'harrys-gravatar-cache'); ?></p>
			<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SDYQTEP5C2MP8&item_name=Harry's+Gravatar+Cache&no_note=1&no_shipping=1&rm=2"><img alt="" border="0" src="<?php echo plugins_url('paypal-donate.png', __FILE__) ?>" width="150"></a>
			<br />
			<a target="_blank" href="http://www.amazon.de/gp/registry/wishlist/38H54YCAQU0LH/ref=cm_wl_rlist_go_o?"><img alt="" border="0" src="<?php echo plugins_url('amazon.de-logo.png', __FILE__) ?>" width="150"></a>
		</div>
	</div>
	<div class="wrap">
		<h1>Harrys Gravatar Cache</h1>
		<small><?php _e('Accelerates the site speed by simply and effective caching Gravatar (Globally Recognized Avatars) so that they are delivered from the own web server and do not need to be reloaded from the Gravatar server.','harrys-gravatar-cache')?></small>		  
		<h2><?php _e('Settings','harrys-gravatar-cache'); ?></h2>
	<?php
	global $wpdb;
	$table=$wpdb->prefix.'harrys_gravatar_cache';
	$size=$wpdb->get_var($wpdb->prepare("SELECT size FROM $table WHERE ID = %d", 1) );
	$size_get=$wpdb->get_var($wpdb->prepare("SELECT size_get FROM $table WHERE ID = %d", 1) );
	$get_option=$wpdb->get_var($wpdb->prepare("SELECT get_option FROM $table WHERE ID = %d", 1) );
	$cache_time=$wpdb->get_var($wpdb->prepare("SELECT cache_time FROM $table WHERE ID = %d", 1) );
	$is_writeable=$wpdb->get_var($wpdb->prepare("SELECT is_writeable FROM $table WHERE ID = %d", 1) );
	$file_get_contents=$wpdb->get_var($wpdb->prepare("SELECT file_get_contents FROM $table WHERE ID = %d", 1) );
	$fopen=$wpdb->get_var($wpdb->prepare("SELECT fopen FROM $table WHERE ID = %d", 1) );
	$curl=$wpdb->get_var($wpdb->prepare("SELECT curl FROM $table WHERE ID = %d", 1) );
	$copy=$wpdb->get_var($wpdb->prepare("SELECT copy FROM $table WHERE ID = %d", 1) );
	$stored_theme=$wpdb->get_var($wpdb->prepare("SELECT active_theme FROM $table WHERE ID = %d", 1) );
	$active_theme_wp=get_option('template');
	$rating = strtolower(get_option('avatar_rating'));
	if($get_option==1){$copy_option="WordPress Filesystem (file_get_contents)";}
	if($get_option==2){$copy_option="WordPress Remote Fopen (fopen / cUrl)";}
	if($get_option==3){$copy_option="WordPress Remote Fopen (fopen / cUrl)";}
	if($get_option==4){$copy_option="PHP copy";}
	$cache_day=0;
	$cache_week=0;
	if($cache_time==1440){$cache_week=0;$cache_day=1;}
	if($cache_time==2880){$cache_week=0;$cache_day=2;}
	if($cache_time==4320){$cache_week=0;$cache_day=3;}
	if($cache_time==5760){$cache_week=0;$cache_day=4;}
	if($cache_time==7200){$cache_week=0;$cache_day=5;}
	if($cache_time==8640){$cache_week=0;$cache_day=6;}
	if($cache_time==10080){$cache_week=1;$cache_day=0;}
	if($cache_time==20160){$cache_week=2;$cache_day=0;}
	if($cache_time==30240){$cache_week=3;$cache_day=0;}
	if($cache_time==40320){$cache_week=4;$cache_day=0;}
	if($cache_time==50400){$cache_week=5;$cache_day=0;}
	if($cache_time==60480){$cache_week=6;$cache_day=0;}
	if($cache_time==70560){$cache_week=7;$cache_day=0;}
	if($cache_time==80640){$cache_week=8;$cache_day=0;}
	$database_set_wrong=0;
	if(empty($size) || $size<20 || $size>200 || empty($size_get) || $size_get==0 || ($get_option>4 || $get_option<0) || empty($cache_time) || $cache_time==0 || ($is_writeable!=0 && $is_writeable!=1) ) {$database_set_wrong=1;}
	$path=wp_upload_dir();
	$cache_url=$path['baseurl']."/gravatar-cache/";
	$path=$path['basedir']."/gravatar-cache/";
	$path_ok=false;
	$pathperm=substr(sprintf('%o', fileperms($path)),-4);
	if (is_dir($path))	{
	$path_ok=1;
	}
	if($file_get_contents==1 || $fopen==1 || $curl==1 || $copy==1){$copy_available=1;}
	?>
	<form id="harrys-gravatar-cache" action="" method="post">
		<?php if($database_set_wrong==1){?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<td><font style="font-size:20px;color:red;">
						<?php printf(__('Attention!! The database table "%1$s" could not be filled with the neccessary options for this plugin!!','harrys-gravatar-cache'),$table); ?>
						</font><br><br>
						<?php _e('Here is an overview of the options:','harrys-gravatar-cache');?><br>
						<?php _e('Gravatar Size:','harrys-gravatar-cache');?>
						<?php if(empty($size) || $size==0){?><font style="color:red"><?php _e('false or empty','harrys-gravatar-cache');?></font>, <?php _e('should be a number between 20 and 200','harrys-gravatar-cache');?><?php } else { ?><font style="color:green">ok</font><?php } ?><br>
						<?php _e('How to get the Gravatar Size:','harrys-gravatar-cache');?>
						<?php if(empty($size_get) || $size_get==0){?><font style="color:red"><?php _e('false or empty','harrys-gravatar-cache');?></font>, <?php _e('should be a number between 1 and 3','harrys-gravatar-cache');?><?php } else { ?><font style="color:green">ok</font><?php } ?><br>
						<?php _e('Copy option:','harrys-gravatar-cache');?>
						<?php if(empty($get_option)){?><font style="color:red"><?php _e('false or empty','harrys-gravatar-cache');?></font>, <?php _e('should be a number between 1 and 4','harrys-gravatar-cache');?><?php } else { ?><font style="color:green">ok</font><?php } ?><br>
						<?php _e('Cache time:','harrys-gravatar-cache');?>
						<?php if(empty($cache_time) || $cache_time==0){?><font style="color:red"><?php _e('false or empty','harrys-gravatar-cache');?></font>, <?php _e('should be a number in seconds between 1440 and 80640','harrys-gravatar-cache');?><?php } else { ?><font style="color:green">ok</font><?php } ?><br>
						<?php _e('Cache folder writeable:','harrys-gravatar-cache');?>
						<?php if(empty($is_writeable)){?><font style="color:red"><?php _e('false or empty','harrys-gravatar-cache');?></font>, <?php _e('should be a number 0 or 1','harrys-gravatar-cache');?><?php } else { ?><font style="color:green">ok</font><?php } ?><br>
						</td>
					</tr>
					<tr valign="top">
						<td>
						<?php _e('These error(s) can not be solved with the plugin settings. Try to enable debugging and check the log-files. After you found a reason for the issue and eliminate this, try to deactivate and deinstall and after this reinstall the plugin.','harrys-gravatar-cache'); ?>
						<br>
						</td>
					</tr>
					<tr valign="top">
						<td>
						<?php printf(__('The database table "%1$s" should look like this:','harrys-gravatar-cache'),$table); ?>
						<br>
						<img src="<?php echo plugin_dir_url( __FILE__ );?>database.png" />
						</td>
					</tr>
				</tbody>
			</table>
		<?php } else { ?>
			<table class="form-table">
				<tbody>
					<?php if(empty($rating)){?>
					<tr valign="top">
						<td><font style="color:orange;">
						<?php _e('Attention!! the rating option for Gravatars is empty. It is set to rating "R" for getting the (Gr)Avatars.','harrys-gravatar-cache');?>
						</font><br>
						<?php _e('Please check the rating on your <a href="options-discussion.php">"Discussion setting page"</a>.','harrys-gravatar-cache');?>
						</td>
					</tr>
					<?php } ?>
					<?php if($path_ok!=1){?>
					<tr valign="top">
						<td><font style="font-size:20px;color:red;">
						<?php _e('Attention!! the caching folder is not exist!!','harrys-gravatar-cache');?>
						</font><br>
						<?php _e('Please press the button to create the caching folder.','harrys-gravatar-cache');?>
						</td>
					</tr>
					<p>
					<tr valign="top">
						<td>
						<input class="button" type="submit" name="make_folder" value="<?php _e('create folder','harrys-gravatar-cache'); ?>"/>
						</td>
					</tr>
					</p>
					<?php } ?>
					<?php if($pathperm!="0755" && $pathperm!="0775" && $path_ok==1){?>
					<tr valign="top">
						<td><font style="font-size:20px;color:red;">
						<?php _e('Attention!! the permissions of the caching folder are not correct!!','harrys-gravatar-cache');?>
						</font><br>
						<?php _e('Please press the button to correct the permissions for the caching folder or change manually the permissions to 0755 or 0775.','harrys-gravatar-cache');?>
						</td>
					</tr>
					<p>
					<tr valign="top">
						<td>
						<input class="button" type="submit" name="correct_folder" value="<?php _e('correct permissions to 0755','harrys-gravatar-cache'); ?>"/>
						</td>
					</tr>
					<tr valign="top">
						<td>
						<input class="button" type="submit" name="correct_folder2" value="<?php _e('correct permissions to 0775','harrys-gravatar-cache'); ?>"/>
						</td>
					</tr>
					</p>
					<?php } ?>
					<?php if($is_writeable!=1 && $path_ok==1){?>
					<tr valign="top">
						<td><font style="font-size:20px;color:red;">
						<?php _e('Attention!! the caching folder is not writeable!!','harrys-gravatar-cache');?>
						</font><br>
						<?php _e('Please change the permissions to 0755 for the caching folder "','harrys-gravatar-cache');?>
						<font style="color:green;"><strong><?php echo $path; ?></strong></font>
						<?php _e('" and check.','harrys-gravatar-cache');?>
						</td>
					</tr>
					<p>
					<tr valign="top">
						<td>
						<input class="button" type="submit" name="is_writeable" value="<?php _e('check if the caching folder is writeable','harrys-gravatar-cache'); ?>"/>
						</td>
					</tr>
					</p>
					<?php } ?>
					<?php if(($get_option>4 || $get_option<1) && $is_writeable==1 && $path_ok==1){?>
					<tr valign="top">
						<td><font style="font-size:20px;color:red;">
						<?php _e('Attention!! NO option for getting the Gravatars is available!!','harrys-gravatar-cache');?>
						</font><br>
						<?php _e('Please contact your hoster to make one of these functions available:','harrys-gravatar-cache');?>
						<br>"file_get_contents"<br>"fopen"<br>"cUrl"<br>"PHP copy"</td>
					</tr>
					<p>
					<tr valign="top">
						<td>
						<input class="button" type="submit" name="get_copy_options" value="<?php _e('check the server for available copy options','harrys-gravatar-cache'); ?>"/>
						</td>
					</tr>
					</p>
					<?php } ?>
				</tbody>
			</table>
			<hr>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e('current Gravatar size:','harrys-gravatar-cache'); ?></th><td><?php echo $size; ?> px, <?php if($size_get==1){_e('set by template','harrys-gravatar-cache');if($active_theme_wp!=$stored_theme){echo "&nbsp;-&nbsp;"; _e('Attention!! The template has changed!!','harrys-gravatar-cache');}}else if($size_get==2){_e('set by plugin','harrys-gravatar-cache');}else if($size_get==3){_e('set by you','harrys-gravatar-cache');} ?></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('change Gravatar size:','harrys-gravatar-cache'); ?></th>
						<td><select name="size">
						<?php if($size!=40 && $size!=44 && $size!=67 && $size!=80 && $size!=96 && $size!=120) {?><option value="<?php echo $size; ?>" <?php if($size_get!=1) echo "selected=selected";?>><?php echo $size; ?> px, <?php if($size_get==1){_e('set by template','harrys-gravatar-cache');}else if($size_get==2){_e('set by plugin','harrys-gravatar-cache');}else if($size_get==3){_e('set by you','harrys-gravatar-cache');} ?></option><?php } ?>
						<option value="40" <?php if($size==40) echo "selected=selected";?>>40 px<?php if($size_get==1 && $size==40){ ?>, <?php _e('set by template','harrys-gravatar-cache');}else if($size_get==2 && $size==40){ ?>, <?php _e('set by plugin','harrys-gravatar-cache');}else if($size_get==3 && $size==40){ ?>, <?php _e('set by you','harrys-gravatar-cache');} ?></option>
						<option value="44" <?php if($size==44) echo "selected=selected";?>>44 px<?php if($size_get==1 && $size==44){ ?>, <?php _e('set by template','harrys-gravatar-cache');}else if($size_get==2 && $size==44){ ?>, <?php _e('set by plugin','harrys-gravatar-cache');}else if($size_get==3 && $size==44){ ?>, <?php _e('set by you','harrys-gravatar-cache');} ?></option>
						<option value="67" <?php if($size==67) echo "selected=selected";?>>67 px<?php if($size_get==1 && $size==67){ ?>, <?php _e('set by template','harrys-gravatar-cache');}else if($size_get==2 && $size==67){ ?>, <?php _e('set by plugin','harrys-gravatar-cache');}else if($size_get==3 && $size==67){ ?>, <?php _e('set by you','harrys-gravatar-cache');} ?></option>
						<option value="80" <?php if($size==80) echo "selected=selected";?>>80 px<?php if($size_get==1 && $size==80){ ?>, <?php _e('set by template','harrys-gravatar-cache');}else if($size_get==2 && $size==80){ ?>, <?php _e('set by plugin','harrys-gravatar-cache');}else if($size_get==3 && $size==80){ ?>, <?php _e('set by you','harrys-gravatar-cache');} ?></option>
						<option value="96" <?php if($size==96) echo "selected=selected";?>>96 px<?php if($size_get==1 && $size==96){ ?>, <?php _e('set by template','harrys-gravatar-cache');}else if($size_get==2 && $size==96){ ?>, <?php _e('set by plugin','harrys-gravatar-cache');}else if($size_get==3 && $size==96){ ?>, <?php _e('set by you','harrys-gravatar-cache');} ?></option>
						<option value="120" <?php if($size==120) echo "selected=selected";?>>120 px<?php if($size_get==1 && $size==120){ ?>, <?php _e('set by template','harrys-gravatar-cache');}else if($size_get==2 && $size==120){ ?>, <?php _e('set by plugin','harrys-gravatar-cache');}else if($size_get==3 && $size==120){ ?>, <?php _e('set by you','harrys-gravatar-cache');} ?></option>
						</select></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('or enter Gravatar size manually (20-200 px):','harrys-gravatar-cache'); ?></th>
						<td><input pattern="[0-9]{2,4}" style="width:75px" min="20" max="200" step="1" name="size_man" type="number" value="" /> px
						</td>
					</tr>
					<?php if($size_get!=1 || $active_theme_wp!=$stored_theme){ ?>
					<p>
					<tr valign="top">
						<td>
						<input class="button" type="submit" name="get_size_gravatar_hgc" value="<?php _e('Try to get the Gravatar size from the template','harrys-gravatar-cache'); ?>"/>
						</td>
					</tr>
					</p>
					<?php } ?>
					<?php if($is_writeable==1 && $path_ok==1 && $copy_available==1){?>
					<tr valign="top">
						<th scope="row"><?php _e('your server accepts the following copy options to get the Gravatar:','harrys-gravatar-cache'); ?></th><td><?php if($file_get_contents==1){echo "file_get_contents (<strong>wp_filesystem</strong>)<br>";}if($fopen==1){echo "fopen (<strong>wp_remote_fopen</strong>)<br>";}if($curl==1){echo "cUrl (<strong>wp_remote_fopen</strong>)<br>";}if($copy==1){echo "PHP copy<br>";}?></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('current copy option:','harrys-gravatar-cache'); ?></th><td><?php echo $copy_option; ?></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('change copy option:','harrys-gravatar-cache'); ?></th>
						<td><select name="copy_option">
						<?php if($file_get_contents==1){?><option value="1" <?php if($get_option==1) echo "selected=selected";?>>WordPress Filesystem (file_get_contents)</option><?php } ?>
						<?php if($fopen==1 || $curl==1){?><option value="2" <?php if($get_option==2 || $get_option==3) echo "selected=selected";?>>WordPress Remote Fopen (fopen / cUrl)</option><?php } ?>
						<?php if($copy==1){?><option value="4" <?php if($get_option==4) echo "selected=selected";?>>PHP copy</option><?php } ?>
						</select></td>
					</tr>
					<p>
					<tr valign="top">
						<td>
						<input class="button" type="submit" name="get_copy_options" value="<?php _e('check the server for available copy options','harrys-gravatar-cache'); ?>"/>
						</td>
					</tr>
					</p>
					<?php }?>
					<tr valign="top">
						<th scope="row"><?php _e('current Cache time:','harrys-gravatar-cache'); ?></th><td><?php if($cache_week>0){echo $cache_week;}if($cache_day>0){echo $cache_day;} if($cache_week<2 && $cache_day==0){_e(' week','harrys-gravatar-cache');}else if($cache_week>1 && $cache_day==0){_e(' weeks','harrys-gravatar-cache');}else if($cache_day<2 && $cache_week==0){_e(' day','harrys-gravatar-cache');}else if($cache_day>1 && $cache_week==0){_e(' days','harrys-gravatar-cache');}?></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('change Cache time:','harrys-gravatar-cache'); ?></th>
						<td><select name="cache-time">
						<option value="1440" <?php if($cache_time==1440) echo "selected=selected";?>>1<?php _e(' day','harrys-gravatar-cache');?></option>
						<option value="2880" <?php if($cache_time==2880) echo "selected=selected";?>>2<?php _e(' days','harrys-gravatar-cache');?></option>
						<option value="4320" <?php if($cache_time==4320) echo "selected=selected";?>>3<?php _e(' days','harrys-gravatar-cache');?></option>
						<option value="5760" <?php if($cache_time==5760) echo "selected=selected";?>>4<?php _e(' days','harrys-gravatar-cache');?></option>
						<option value="7200" <?php if($cache_time==7200) echo "selected=selected";?>>5<?php _e(' days','harrys-gravatar-cache');?></option>
						<option value="8640" <?php if($cache_time==8640) echo "selected=selected";?>>6<?php _e(' days','harrys-gravatar-cache');?></option>
						<option value="10080" <?php if($cache_time==10080) echo "selected=selected";?>>1<?php _e(' week','harrys-gravatar-cache');?></option>
						<option value="20160" <?php if($cache_time==20160) echo "selected=selected";?>>2<?php _e(' weeks','harrys-gravatar-cache');?></option>
						<option value="30240" <?php if($cache_time==30240) echo "selected=selected";?>>3<?php _e(' weeks','harrys-gravatar-cache');?></option>
						<option value="40320" <?php if($cache_time==40320) echo "selected=selected";?>>4<?php _e(' weeks','harrys-gravatar-cache');?></option>
						<option value="50400" <?php if($cache_time==50400) echo "selected=selected";?>>5<?php _e(' weeks','harrys-gravatar-cache');?></option>
						<option value="60480" <?php if($cache_time==60480) echo "selected=selected";?>>6<?php _e(' weeks','harrys-gravatar-cache');?></option>
						<option value="70560" <?php if($cache_time==70560) echo "selected=selected";?>>7<?php _e(' weeks','harrys-gravatar-cache');?></option>
						<option value="80640" <?php if($cache_time==80640) echo "selected=selected";?>>8<?php _e(' weeks','harrys-gravatar-cache');?></option>
						</select></td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<?php wp_nonce_field( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options', false ); ?>
				<input class="button-primary" type="submit" name="harry_gravatar_save" value="<?php _e('Save changes','harrys-gravatar-cache'); ?>"/>
				<input class="button" type="submit" name="harry_gravatar_empty_cache" value="<?php _e('Empty Cache','harrys-gravatar-cache'); ?>"/>
			</p>
			<hr>
			<?php if($get_option<5 && $get_option>0 && $is_writeable==1 && $path_ok==1 && ($pathperm=="0755" || $pathperm=="0775") ) { ?>
			<h2><?php _e('Statistics','harrys-gravatar-cache'); ?></h2>
			<table class="form-table">
				<tbody>
				<?php
				if ($dh = opendir($path)) {
				$count=0;
				$filesize=0;
					while (($file = readdir($dh)) !== false) {
						if ($file!="." AND $file !="..") {
						$count++;
						$filesize=filesize($path.$file)+$filesize;
						}
					}
					closedir($dh);
				}
				if($filesize>1024000){
				$filesize_show=round($filesize/1024/1024, 2);
				$filesize_show=$filesize_show." MBytes";
				}
				else if($filesize>10240){
				$filesize_show=round($filesize/1024, 2);
				$filesize_show=$filesize_show." kBytes";
				}
				else {
				$filesize_show=$filesize." Bytes";
				}
				?>
					<tr valign="top">
						<th scope="row"><?php _e('Number of files in cache:','harrys-gravatar-cache'); ?></th><td><?php echo $count; ?></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Filesize of cached files:','harrys-gravatar-cache'); ?></th><td><?php echo $filesize_show; ?></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Cache URL:','harrys-gravatar-cache'); ?></th><td><?php echo $cache_url; ?></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Cache Path:','harrys-gravatar-cache'); ?></th><td><?php echo $path; ?></td>
					</tr>
				</tbody>
			</table>
			<?php } ?>
	<?php } ?>
		</form>
		</div>
<?php } else {
	wp_die( __( 'You do not have sufficient permissions to access this page.', 'harrys-gravatar-cache' ) );
	}
}
  
/* Einstellungen speichern */
function save_settings() {
	global $wpdb;
	$table=$wpdb->prefix.'harrys_gravatar_cache';
	$path=wp_upload_dir();
	$path=$path['basedir']."/gravatar-cache/";
	if(stripos($_SERVER['REQUEST_URI'],'/options-general.php?page=harrys-gravatar-cache-options')!==FALSE) {
	global $wpdb;
		if( (isset($_POST['size']) || isset($_POST['size_man']) ) && isset($_POST['harry_gravatar_save']) && !isset($_POST['get_size_gravatar_hgc']) && !isset($_POST['harry_gravatar_empty_cache']) && !isset($_POST['get_copy_options']) && !isset($_POST['is_writeable']) && !isset($_POST['make_folder']) && !isset($_POST['correct_folder']) && !isset($_POST['correct_folder2'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		$size=$wpdb->get_var($wpdb->prepare("SELECT size FROM $table WHERE ID = %d", 1) );
			if($size!=$_POST['size'] || $size!=$_POST['size_man']) {
				if($_POST['size_man']!=0 || !empty($_POST['size_man'])) {
					if($size!=$_POST['size_man']) {
					$wpdb->update($table, array('size' => $_POST['size_man'], 'size_get' => '3'), array('id' => 1), array('%d', '%d'));
					empty_cache($path);
					} else {
					$wpdb->update($table, array('size' => $_POST['size_man']), array('id' => 1), array('%d', '%d'));
					}
				} else {
					if($size!=$_POST['size']) {
					$wpdb->update($table, array('size' => $_POST['size'], 'size_get' => '3'), array('id' => 1), array('%d', '%d'));
					empty_cache($path);
					} else {
					$wpdb->update($table, array('size' => $_POST['size']), array('id' => 1), array('%d', '%d'));
					}
				}
			}
		}
		if(isset($_POST['copy_option']) && isset($_POST['harry_gravatar_save']) && !isset($_POST['get_size_gravatar_hgc']) && !isset($_POST['harry_gravatar_empty_cache']) && !isset($_POST['get_copy_options']) && !isset($_POST['is_writeable']) && !isset($_POST['make_folder']) && !isset($_POST['correct_folder']) && !isset($_POST['correct_folder2'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		$get_option=$wpdb->get_var($wpdb->prepare("SELECT get_option FROM $table WHERE ID = %d", 1) );
			if($get_option!=$_POST['copy_option']){
			$wpdb->update($table, array('get_option' => $_POST['copy_option']), array('id' => 1), array('%d'));
			}
		}
		if(isset($_POST['cache-time']) && isset($_POST['harry_gravatar_save']) && !isset($_POST['get_size_gravatar_hgc']) && !isset($_POST['harry_gravatar_empty_cache']) && !isset($_POST['get_copy_options']) && !isset($_POST['is_writeable']) && !isset($_POST['make_folder']) && !isset($_POST['correct_folder']) && !isset($_POST['correct_folder2'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		$cache_time=$wpdb->get_var($wpdb->prepare("SELECT cache_time FROM $table WHERE ID = %d", 1) );
			if(empty($cache_time)){$cache_time=40320;}
			if(!empty($_POST['cache-time'])){
				if($cache_time!=$_POST['cache-time']){
				$wpdb->update($table, array('cache_time' => $_POST['cache-time']), array('id' => 1), array('%d'));
				}
			}
		}
		if(isset($_POST['make_folder'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		make_folder($path);
		is_writeable_proof($table, $path);
		}
		if(isset($_POST['correct_folder'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		correct_folder($path);
		is_writeable_proof($table, $path);
		}
		if(isset($_POST['correct_folder2'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		correct_folder2($path);
		is_writeable_proof($table, $path);
		}
		if(isset($_POST['is_writeable'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		is_writeable_proof($table, $path);
		}
		if(isset($_POST['get_size_gravatar_hgc'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		$size1=$wpdb->get_var($wpdb->prepare("SELECT size FROM $table WHERE ID = %d", 1) );
		get_size_gravatar_hgc($table, $path);
		$size2=$wpdb->get_var($wpdb->prepare("SELECT size FROM $table WHERE ID = %d", 1) );
			if($size1!=$size2) {
			empty_cache($path);
			}
		}
		if(isset($_POST['get_copy_options'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		get_copy_options($table, $path);
		}
		if(isset($_POST['harry_gravatar_empty_cache'])) {
		check_ajax_referer( 'harrys_gravatar_cache_options', 'harrys_gravatar_cache_options' );
		empty_cache($path);
		}
	}
}

/* Caching und return Funktion */
function gravatar_lokal ($avatar, $id_or_email, $size, $default, $alt) {
	global $wp_filesystem;
	if (empty($wp_filesystem)) {
		require_once ( ABSPATH . "/wp-admin/includes/file.php" );
		WP_Filesystem();
	}
	global $in_comment_loop;
	if( isset($in_comment_loop) || in_the_loop() || is_singular() || is_author() )
	{
			preg_match( '/class=\'(.*?)\'/s', $avatar, $css);
			if(!empty($css)){$css=$css[1];}
			preg_match( '/src=\'(.*?)\'/s', $avatar, $src_proof);
			if(!empty($src_proof)){$src_proof=$src_proof[1];}
			if(empty($css)){
				preg_match( '/class=\"(.*?)\"/s', $avatar, $css);
				if(!isset($css[1])) {
					$css[1]=null;
				}
			$css=$css[1];
			}
			if(empty($src_proof)){
				preg_match( '/src=\"(.*?)\"/s', $avatar, $src_proof);
				$src_proof=$src_proof[1];
			}
			$new_host=1;
			if(strpos($src_proof,'https')===false && strpos($src_proof,'http')===false) {
				$new_host=0;
			}
			global $wpdb;
			$table=$wpdb->prefix.'harrys_gravatar_cache';
			$cache_time=$wpdb->get_var($wpdb->prepare("SELECT cache_time FROM $table WHERE ID = %d", 1) );
			$path=wp_upload_dir();
			$path_file=$path['baseurl']."/gravatar-cache/";
			$path=$path['basedir']."/gravatar-cache/";
			$no_mail=0;
			$mail=@get_comment_author_email();
			if(empty($mail)) {
				if(in_the_loop()) {
					$mail=@get_the_author_meta('user_email');
				} else if(is_author()) {
					if(isset($_GET['author_name'])) {
						$mail=@get_user_by($author_name,$author_name);
						$mail=$mail->user_email;
					} else {
						if(is_numeric($id_or_email)) {
							$mail=@get_userdata($id_or_email);
							$mail=$mail->user_email;
						} else if (filter_var($id_or_email, FILTER_VALIDATE_EMAIL)) {
							$mail=$id_or_email;
						}
					}
				} else {
					$mail=@get_comment_author_url();
					$no_mail=1;
				}
			}
			$author=@get_comment_author();
			if(in_the_loop()) {
				$author=@get_the_author();
			}
			//Unterstützung für Avatar Manager
			if( !function_exists('is_plugin_active') ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			if (is_plugin_active('avatar-manager/avatar-manager.php') ) {
				$avatar_type="not set";
				if ( is_numeric( $id_or_email ) ) {
					$id = (int) $id_or_email;
					$user = get_userdata( $id );
					if ( $user ) {
						$email = $user->user_email;
					}
				} elseif ( is_object( $id_or_email ) ) {
					if ( ! empty( $id_or_email->user_id ) ) {
						$id = (int) $id_or_email->user_id;
						$user = get_userdata( $id );
						if ( $user ) {
							$email = $user->user_email;
						}
					} elseif ( ! empty( $id_or_email->comment_author_email ) ) {
						$email = $id_or_email->comment_author_email;
					}
				} else {
					$email = $id_or_email;
					if ( $id = email_exists( $email ) ) {
						$user = get_userdata( $id );
					}
				}
				if ( isset( $user ) ) {
					$avatar_type = $user->avatar_manager_avatar_type;
				}
				if ( $avatar_type == 'custom' ) {
					return $avatar;
				}
			}
			if(empty($alt)){$alt=$author;}
			$filename=md5( strtolower( $mail ) );
			$cachetime = $cache_time * 60;
			if ( $in_comment_loop == true ) {
				$size=$wpdb->get_var($wpdb->prepare("SELECT size FROM $table WHERE ID = %d", 1) );
			}
			if( in_the_loop() && $in_comment_loop == false ) {
				$size=$size;
				$filename.="at";
			}
			if( !in_the_loop() && $in_comment_loop == false && is_singular() ) {
				$size=$size;
				$filename.="sb";
			}
			$size_srcset=$size*2;
			$cachefile=$path.$filename.".png";
			$cachefile_srcset=$path.$filename."_2x.png";
			if (strpos($src_proof,'gravatar.com')!==false && $no_mail==0) { //von gravatar.com
				if($new_host==0) {
					$host = '//secure.gravatar.com/avatar';
				}
				else {
					if (is_ssl()) {
						$host = 'https://secure.gravatar.com/avatar';
					}
					else {
						$host = 'http://www.gravatar.com/avatar';
					}
				}
				$rating = strtolower(get_option('avatar_rating'));
				if(empty($rating)){$rating="r";}
				if($default=="dwapuuvatar") {
					$dsize=strripos($src_proof,"-");
					$pic_size = substr($src_proof, $dsize);
					$pic_size = substr($pic_size, 1);
					$pic_size = substr($pic_size, 0, -9);
					if ($folder_pic = opendir(WP_PLUGIN_DIR."/wapuuvatar/dist/")) {
						$count_png=0;
						$allpng=array();
							while (($file = readdir($folder_pic)) !== false) {
								if ($file!="." AND $file !="..") {
									if(strpos($file,$pic_size)) {
									$count_png++;
									$allpng[]=$file;
								}
							}
						}
						closedir($folder_pic);
					}
					$counterhier=count($allpng);
					$random_pic=mt_rand(0,$counterhier);
					$selected_pic=$allpng[$random_pic];
					$default=plugins_url()."/wapuuvatar/dist/".$selected_pic;
				}
				$grav_img = $host."/".$filename."?s=".$size."&d=".$default."&r=".$rating;
				$srcset_img = $host."/".$filename."?s=".$size_srcset."&d=".$default."&r=".$rating;
				$css=$css." grav-hashed grav-hijack";
			}
			else { //von anderen
				if($new_host==0) {
					$host = '//';
				}
				else {
					if (is_ssl()) {
						$host = 'https';
					}
					else {
						$host = 'http';
					}
				}
				if($no_mail==0) { //mit Mail
					$pos = strpos($src_proof, ":");
					$length=strlen ($src_proof);
					$src_proof = substr($src_proof, $pos, $length);
					$src_proof=$host.$src_proof;
					$url_host=parse_url($src_proof, PHP_URL_HOST);
					$pfad=parse_url($src_proof, PHP_URL_PATH);
					if(strpos($pfad,'_____')!==false && $url_host=="pbs.twimg.com") { //für Twitter
						$pfad=str_replace("_____","",$pfad);
					}
					if($url_host=="scontent.xx.fbcdn.net") { //für Facebook
						$filename.="fb";
						$cachefile=$path.$filename.".png";
						$cachefile_srcset=$path.$filename."_2x.png";
					}
					if($url_host=="lh4.googleusercontent.com") { //für Google+
						$filename.="gp";
						$cachefile=$path.$filename.".png";
						$cachefile_srcset=$path.$filename."_2x.png";
					}
					$query=parse_url($src_proof, PHP_URL_QUERY);
					$query=str_replace("__","",$query);
					if(!empty($query)){$query="?".$query;}
					if($new_host==0) {
						$src_proof=$url_host.$pfad.$query;
					}
					else {
						$src_proof=$host.'://'.$url_host.$pfad.$query;
					}
					$grav_img = $src_proof;
					$srcset_img = $src_proof;
				}
				else { //ohne Mail
					$pos = strpos($mail, ":");
					$length=strlen ($mail);
					$mail = substr($mail, $pos, $length);
					$mail=$host.$mail;
					$url_host=parse_url($mail, PHP_URL_HOST);
					$pfad=parse_url($mail, PHP_URL_PATH);
					$query=parse_url($mail, PHP_URL_QUERY);
					if(!empty($query)){$query=str_replace("__","",$query);}
					if(!empty($query)){$query="?".$query;}
					if($url_host=='www.facebook.com') { //von Facebook
						$url_host='graph.facebook.com/';
						$pfad.='/picture';
						if($new_host==0) {
							$mail=$url_host.$pfad.$query;
						}
						else {
							$mail=$host.'://'.$url_host.$pfad.$query;
						}
						$grav_img = $mail;
						$srcset_img = $mail;
						$filename.="fb";
						$cachefile=$path.$filename.".png";
						$cachefile_srcset=$path.$filename."_2x.png";
					}
					else { //von gravatar.com
						if($new_host==0) {
							$host = '//secure.gravatar.com/avatar';
						}
						else {
							if (is_ssl()) {
								$host = 'https://secure.gravatar.com/avatar';
							}
							else {
								$host = 'http://www.gravatar.com/avatar';
							}
						}
						$rating = strtolower(get_option('avatar_rating'));
						if(empty($rating)){$rating="r";}
						$grav_img = $host."/".$filename."?s=".$size."&d=".$default."&r=".$rating;
						$srcset_img = $host."/".$filename."?s=".$size_srcset."&d=".$default."&r=".$rating;
					}
				}
			}
			if (!$wp_filesystem->exists($cachefile) || !$wp_filesystem->exists($cachefile_srcset) || (time() - $cachetime > filemtime($cachefile))) {
				$file_copy=$wpdb->get_var($wpdb->prepare("SELECT get_option FROM $table WHERE ID = %d", 1) );
				if($file_copy==1) { //file_get_contents
					$grav_img=$wp_filesystem->get_contents($grav_img);
					$wp_filesystem->put_contents($cachefile,$grav_img,0644);
					$fileperm=substr(sprintf('%o', @fileperms($cachefile)),-4);
					$srcset_img=$wp_filesystem->get_contents($srcset_img);
					$wp_filesystem->put_contents($cachefile_srcset,$srcset_img,0644);
					$fileperm=substr(sprintf('%o', @fileperms($cachefile_srcset)),-4);
				}
				if($file_copy==2 || $file_copy==3) { //fopen / cUrl
					$fp = wp_remote_fopen($grav_img);
					$wp_filesystem->put_contents($cachefile,$fp,0644);
					$fileperm=substr(sprintf('%o', @fileperms($cachefile)),-4);
					$fp = wp_remote_fopen($srcset_img);
					$wp_filesystem->put_contents($cachefile_srcset,$fp,0644);
					$fileperm=substr(sprintf('%o', @fileperms($cachefile_srcset)),-4);
				}
				if($file_copy==4) { //PHP Copy
					@copy($grav_img, $cachefile);
					$fileperm=substr(sprintf('%o', @fileperms($cachefile)),-4);
					@copy($srcset_img, $cachefile_srcset);
					$fileperm=substr(sprintf('%o', @fileperms($cachefile_srcset)),-4);
				}
				if($fileperm!="0644") {
					@chmod($cachefile,0644);
				}
				if($fileperm!="0644") {
					@chmod($cachefile_srcset,0644);
				}
				$img_info = @getimagesize($path.$filename.".png");
				if(!empty($img_info['channels'])){$farbraum = $img_info['channels'];}
				if(empty($img_info['channels'])){
						$farbraum=null;
				}
				if($farbraum==3) {
					$gravatar_bild = @imageCreateFromJpeg($path.$filename.".png");
					@imageAlphaBlending($gravatar_bild, true);
					@imageSaveAlpha($gravatar_bild, true);
					@imagepng($gravatar_bild,$path.$filename.".png");
					@imagedestroy($gravatar_bild);
				}
				$comp = @imagecreatefrompng($cachefile);
				@imageAlphaBlending($comp, true);
				@imageSaveAlpha($comp, true);
				@imagepng($comp,$path.$filename.".png",9);
				@imagedestroy($comp);
				$comp_src = @imagecreatefrompng($cachefile);
				@imageAlphaBlending($comp_src, true);
				@imageSaveAlpha($comp_src, true);
				@imagepng($comp_src,$path.$filename."_2x.png",9);
				@imagedestroy($comp_src);
			}
			$md5_check=@md5_file($path.$filename.".png");
			foreach (array("") as $scandirName) {
				$scan[$scandirName] = scandir($path.$scandirName);
			}
			foreach ($scan as $scandirName=>$scanneddir) {
				$thisGravatarCacheDir=rtrim($path.$scandirName,"/")."/";
				foreach($scanneddir as $file) {
					if(!in_array($file,array('.','..')) && is_file($thisGravatarCacheDir.$file) && strpos($file,'_2x') === false) {
						$md5_array[]=md5_file($thisGravatarCacheDir.$file);
						$file_array[]=$file;
					}
				}
			}
			unset($key);
			$key = array_search($md5_check, $md5_array);
			if( $key!="" || $key==0 ) {
				$filename=$file_array[$key];
				$filename=str_replace('.png', '', $filename);
			}
			$cachefile_png = $path_file.$filename.".png";
			$srcset = $path_file.$filename.'_2x.png';
			$img_info = @getimagesize($path.$filename.".png");
			$filesize_gravatar=0;
			$filesize_gravatar=filesize($path.$file)+$filesize_gravatar;
			//Fallback falls Caching nicht geklappt hat
			if(!$wp_filesystem->exists($cachefile) || !$wp_filesystem->exists($cachefile_srcset)) {
				return $avatar;
			}
			$count=uniqid();
			$id='grav-'.$filename.'-'.$count;
			if(strpos($_SERVER['HTTP_USER_AGENT'],'rv:11.0')!==false) {
				return "<img id='{$id}' alt='{$alt}' src='{$cachefile_png}' srcset='{$srcset} 2x' class='{$css}' height='{$size}' width='{$size}' />";
			}
			elseif(strpos($_SERVER['HTTP_USER_AGENT'],'Validator.nu')!==true)  {
				return "<img id='{$id}' alt='{$alt}' src='{$cachefile_png}' srcset='{$srcset} 2x' class='{$css}' height='{$size}' width='{$size}' />";
			}
			else {
				return "<img id='{$id}' alt='{$alt}' src='{$cachefile_png}' srcset='{$srcset} 2x' scale='1.5' originals='{$size}' src-orig='{$cachefile_png}' class='{$css}' height='{$size}' width='{$size}' />";
			}
	}
	else
	{
		return $avatar;
	}
}

/* vor Funktionsaufruf prüfen ob Cache-Ordner vorhanden und beschreibbar */
$path=wp_upload_dir();
$path=$path['basedir']."/gravatar-cache/";
$path_ok=false;
if (is_dir($path))	{
$path_ok=1;
}
$is_writeable=$wpdb->get_var($wpdb->prepare("SELECT is_writeable FROM $table WHERE ID = %d", 1) );
$size=$wpdb->get_var($wpdb->prepare("SELECT size FROM $table WHERE ID = %d", 1) );
$size_get=$wpdb->get_var($wpdb->prepare("SELECT size_get FROM $table WHERE ID = %d", 1) );
$get_option=$wpdb->get_var($wpdb->prepare("SELECT get_option FROM $table WHERE ID = %d", 1) );
$cache_time=$wpdb->get_var($wpdb->prepare("SELECT cache_time FROM $table WHERE ID = %d", 1) );
/* Funktionsaufruf wenn Avatare aktiviert und Cacheordner vorhanden und beschreibbar, size passende Größe, Copyoption verfügbar, Cachetime gesetzt und der User(falls eingeloggt) kein Admin ist */
if(get_option('show_avatars') && $path_ok==1 && $is_writeable==1 && $size>19 && $size<201 && $size_get>0 && $size_get<4 && $get_option>0 && $get_option<5 && $cache_time>1439 && $cache_time<80641 && !current_user_can('manage_options')) {
	add_filter('get_avatar', 'gravatar_lokal', 16, 5);
}