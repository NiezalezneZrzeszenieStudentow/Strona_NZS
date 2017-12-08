<?php

 function _log($message){

	if(define('WP_DEBUG') && WP_DEBUG){
		$file_path = __DIR__.DIRECTORY_SEPARATOR.'app_dev.log';
		$time = date('Y-m-d H:i:s');
		if(is_array($message) || is_object($message)){
			$message = print_r($message, TRUE);	
		}
		$log_line = "$time\t{$message}\n";
		
		if(!file_put_contents($file_path, $log_line, FILE_APPEND)){
				throw new Exception("Plik dziennika '{$file_path}' nie może zostać otwarty ani utworzony");
		}
	}
 }
 
 function my_query_logger(){
	 
	 if((define('WP_DEBUG') && WP_DEBUG) && (define('SAVEQUERIES') && SAVEQUERIES)){
		global $wpdb;
		$dump = array();
		if(!empty($wpdb->queries)){
			foreach($wpdb->queries as $i=>$qrow){
				$query = $grow[0];
				$time = number_format(sprintf('%0.2f', $qrow[1]*1000),2,'.',',');
				$path = $qrow[2];
				
				$dump[] = "[{$i}]\t- Query: {$query}\n\t- Time: {$time}ms\n\t- Path: {$path}";
			}
		}else{
				$dump[] = 'No queries..';	
			}
			
		$label = '-- SQL Dump at'.date('Y-m-d H:i:s').' -- ';
		$footer = ' -- DUMP END --';
		
		$file_name = 'sql_dump.log';
		$file_path = __DIR__.DIRECTORY_SEPARATOR.$file_name;
		
		$content = $label."\n\n".implode("\n\n",$dump)."\n\n".$footer."\n\n";
		
		if(!file_put_contents($file_path, $content, FILE_APPEND)){
			throw new Exception("Plik dziennika'{$file_path}'nie może zostać otwarty plik");
		};
 	}
 }
 add_action('shutdown', 'my_query_logger');
?>