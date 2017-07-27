<?php

define('PATH_LOGS', PATH_APP . "logs".DS."error.log");
define('PATH_LOGS_DEBUG', PATH_APP.  "logs".DS."logs-debug.log");

ini_set("error_log" , PATH_LOGS);

/*
* Permita crear logs de Errores, de Información o de Depuración
*/
abstract class logs { 

	/* 
    * Log a nivel de usuario de tipo INFORMACION
	*/ 
	public static function info($message){ 
		$date = date('d/M/Y H:i:s'); 
		$log = '['.$date.' '.date_default_timezone_get().'][INFO] '.$message. PHP_EOL; 
		error_log($log, 3, PATH_LOGS); 
	} 
	

    /* 
    * Log a nivel de usuario de tipo ERROR
	*/     
	public static function error($message) { 
		$date = date('d/M/Y H:i:s'); 
		$log = '['.$date.' '.date_default_timezone_get().'][ERROR] ' .$message. PHP_EOL; 
		error_log($log, 3, PATH_LOGS); 
	} 


    /* 
    * Log a nivel de usuario de tipo DEBUG
	*/ 
    public static function debug($message) { 
		$date = date('d/M/Y H:i:s'); 
		$log = '['.$date.' '.date_default_timezone_get().'][DEBUG] ' .$message. "\n"; 
		error_log($log, 3, PATH_LOGS_DEBUG); 
	} 
} 

