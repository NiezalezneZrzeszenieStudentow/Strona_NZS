<?php
/*
 *Plugin Name: NZS Slider
 *Plugin URI:http://www.nzs.put.poznan.pl
 *Description: Zarządanie sliderem.
 *Version: 1.0
 *Author: Alejski Dawid
 *Author URI: http://www.alejskidawid.pl
 *License: GPL2
 */ 
	require_once 'libs/NzsHomeSlider_model.php';

	class NzsHomeSlider {

		private static $plugin_id = 'nzs-home-slider';
		// do tworzenia unikalnych podstron wtyczki
		private $plugin_version = '1.0.0';
		//zapiszemy do tabeli options
		private $model;
		// służy do komunikacji z bazą danych
		function __construct() {
			$this->model = new NzsHomeSlider_Model();

			register_activation_hook(__FILE__,array($this,'onActivate'));
			//podpinanie metody onActivate 
		}

		function onActivate(){
			//funkcja odpowiada za proces instalacji pluginu wraz z bazą
			$ver_opt = static::$plugin_id.'-version';
			//zmienna bedzie służyć to kontroli czy w jest już zainstalowany plugin lub czy nie jest zainstalowana stara wersja
			$installed_version = get_option($ver_opt , NULL);

			if($installed_version == NULL){

				$this->model -> createDbTable();
				update_option($ver_opt,$this->plugin_version);
			}else{
				switch(version_compare($installed_version, $this->plugin_version)){
					case 0:
					//zainstalowana werja jest identyczna
					break;
					case 1:
					//zainstalowana wersja jest nowsza niż ta
					break;
					case -1:
					//zainstalowana wersja jest starsza niż ta
					break;
				}
			}
		}
	}


	$NzsHomeSlider = new NzsHomeSlider();

?>