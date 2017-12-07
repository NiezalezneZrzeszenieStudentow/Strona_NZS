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
	require_once 'libs/Request.php';

	class NzsHomeSlider {

		private static $plugin_id = 'nzs-home-slider';
		// do tworzenia unikalnych podstron wtyczki
		private $plugin_version = '1.0.0';
		//zapiszemy do tabeli options
		private $model;
		// służy do komunikacji z bazą danych
		private $user_copability = 'manage_options';
		//zmienna definiująca zdolnośc funkcji 
		function __construct() {
			$this->model = new NzsHomeSlider_Model();

			register_activation_hook(__FILE__,array($this,'onActivate'));
			//podpinanie metody onActivate 

			//rejestracja przycisku w menu
			add_action('admin_menu', array($this,'createAdminMenu'));
			//rejestracja skryptów panelu admina

			 //rejestracja skryptów panelu admina
              add_action('admin_enqueue_scripts', array($this, 'addAdminPageScripts'));
             
             
             //rejestracja akcji AJAX
             add_action('wp_ajax_checkValidPosition', array($this, 'checkValidPosition'));
             add_action('wp_ajax_getLastFreePosition', array($this, 'getLastFreePosition'));
         }
         
         
         function addAdminPageScripts(){
             
             wp_register_script(
                     'nzs-hs-script', 
                     plugins_url('/js/scripts.js', __FILE__), 
                     array('jquery', 'media-upload', 'thickbox')
                );
             
             if(get_current_screen()->id == 'toplevel_page_'.static::$plugin_id){
                 
                 wp_enqueue_script('jquery');
                 
                 wp_enqueue_script('thickbox');
                 wp_enqueue_style('thickbox');
                 
                 wp_enqueue_script('media-upload');
                 
                 wp_enqueue_script('nzs-hs-script');
                 
             }
             
         }

         
         function checkValidPosition(){
             
             $position = isset($_POST['position']) ? (int)$_POST['position']: 0;
             
             $message = '';
             
             if($position < 1){
                 $message = 'Podana wartość jest niepoprawna. Pozycja musi być liczbą większą od 0.';
                 
             }else
             if(!$this->model->isEmptyPosition($position)){
                 $message = 'Dana pozycja jest już zajęta';
                 
             }else{
                 $message = 'Ta pozycja jest wolna';
                 
             }
             
             echo $message;
             die;
         }

         function getLastFreePosition(){
         	echo $this->model->getLastFreePosition();
         	die;
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

		function createAdminMenu(){
			add_menu_page(
				'NZS Slider',
				'NZS Home Slider',
				$this->user_copability,
				static::$plugin_id,
				array($this, 'printAdminPage')
			);
			//dodanie do menu Wordpressa opcji do slidera
		}
		function printAdminPage(){
			$request = Request::instance();

			$view = $request->getQuerySingleParam('view','index');

			switch ($view){
				case 'index':
				$this->render('index');
				break;
				case 'form':
				$this->render('form');
				break;
				default:
					$this->render('404');
					break;
			}
			//dynamiczny routing miedzy stronami
			
		}
	


	private function render($view){
			$tmpl_dir = plugin_dir_path(__FILE__).'templates/';
			$view = $tmpl_dir.$view.'.php';

			require_once $tmpl_dir.'layout.php';
	} 
	
}

	$NzsHomeSlider = new NzsHomeSlider();

?>