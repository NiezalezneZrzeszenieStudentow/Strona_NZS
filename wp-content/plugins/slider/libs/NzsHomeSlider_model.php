<?php 

class NzsHomeSlider_Model{
	private $table_name = "nzs_home_slider";
	//nazwa tabeli w bazie danych
	private $wpdb;


	function __construct(){
		global $wpdb;
		$this ->wpdb = $wpdb;

	}

	function getTableName(){
		return $this->wpdb->prefix.$this->table_name;
	}
	//zwraca nazwe tabeli wraz z prefiksem (wp_)

	function createDbTable(){
			$table_name = $this->getTableName();

		$sql = '
			CREATE TABLE IF NOT EXISTS '.$table_name.'(
			id INT NOT NULL AUTO_INCREMENT,
			slider_url VARCHAR(255) NOT NULL,
			title VARCHAR(255) NOT NULL,
			caption VARCHAR(255) DEFAULT NULL,
			read_more_url VARCHAR(255) DEFAULT NULL,
			position INT NOT NULL,
			published enum("yes","no") NOT NULL DEFAULT "yes",
			PRIMARY KEY(id)
			)ENGINE=InnoDB DEFAULT CHARSET=utf8';

		require_once ABSPATH.'wp-admin/includes/upgrade.php';
		// dodanie funkcji dbDelta() wordpressa
		dbDelta($sql);
		//wprowadza zmiany jesli tabela już istnieje

	}
    function isEmptyPosition($position){
        $position = (int)$position;
        $table_name = $this->getTableName();
        
        $sql = "SELECT COUNT(*) FROM {$table_name} WHERE position = %d";
        $prep = $this->wpdb->prepare($sql, $position);
        
        $count = (int)$this->wpdb->get_var($prep);
        
        return ($count < 1);
    }
    
    
    function getLastFreePosition(){
        $table_name = $this->getTableName();
        $sql = "SELECT MAX(position) FROM {$table_name}";
        $pos = (int)$this->wpdb->get_var($sql);
        
        return ($pos+1);
    }
}