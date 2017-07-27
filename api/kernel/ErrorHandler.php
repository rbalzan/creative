<?php

class ErrorHandler extends Exception
{
    public $status;
	public $code;
	
    public function __construct($status, $statusText, $debug = '')
    {
		$this->code = $status;
        $this->status = $status;
        $this->statusText = $statusText;
        $this->debug = array(
			"message" => $debug,
			"File: " => $this->file,
			"Line: " => $this->line
		);
    }

}


set_exception_handler(
	function ($ex){
		
		$view = Creative::get( 'View' );
		
		$body = array(
	    	"status"  => $ex->status,
	    	"statusHttp" => $ex->code,
	        "statusText" => $ex->statusText,
			"icon"=>'error'
	    );
	    
	    if( ENVIRONMENT == 'development' ){
			$body["debug"] = $ex->debug;
		}
	    
	    if ($ex->getCode()) {
	        $view->status = $ex->getCode();
	    } else {
	        $view->status = 500;
	    }
	    $view->response($ex->status, $body);
	}
);



if (!defined('PATH_ERROR_LOGS')) define('PATH_ERROR_LOGS', ROOT.DS."logs".DS."error.log");
if (!defined('PATH_ERROR_LOGS_USER')) define('PATH_ERROR_LOGS_USER', ROOT.DS."logs".DS."error_user.log");

ini_set("error_log" , PATH_ERROR_LOGS);

abstract class log { 
	/* 
	User Errors... 
	*/ 
	public static function error_user($msg,$username){ 
		$date = date('d/M/Y H:i:s'); 
		$log = '['.$date.' '.date_default_timezone_get().']['.$username.'] '.$msg. "\n"; 
		error_log($log, 3, PATH_ERROR_LOGS_USER); 
	} 
	/* 
	General Errors... 
	*/ 
	public static function error($msg) { 
		$date = date('d/M/Y H:i:s'); 
		$log = '['.$date.' '.date_default_timezone_get().'] ' .$msg. "\n"; 
		error_log($log, 3, PATH_ERROR_LOGS); 
	} 

} 



/**
* Manejador de errores
* 
* @param undefined $codigo
* @param undefined $message
* @param undefined $file
* @param undefined $line
* 
* @return
*/
function ErrorHandler($code, $message, $file, $line){
    if (!(error_reporting() & $code)) {        
        return; // Este código de error no está incluido en error_reporting
    }
    
    $view = new View();
    
    switch ($code) {
	    case E_USER_ERROR:
	        $message = 'ERROR: ' . $message;
	    break;
	        
	    case E_USER_WARNING:
	        $message = 'WARNING: ' . $message;
	    break;
	    
	    case E_USER_NOTICE:
			$message = 'NOTICE: ' . $message;
		break;
	        
		default:
		    $message = 'UNDEFINED: ' . $message;
	    break;
	        
    }

	$body = array(
    	"status"  => $code,
    	"statusHttp" => 500,
        "statusText" => $message,
    );
    
    if( ENVIRONMENT == 'development' ){
		$body["debug"] = array("Line: " => $line ,"File: " => $file);
	}
	$view->response(500,$body);
	

    return true;
}
set_error_handler("ErrorHandler");



function shutdown() {
    $isError = false;

    if ($error = error_get_last()){
		switch($error['type']){
	        case E_ERROR:
	        case E_CORE_ERROR:
	        case E_COMPILE_ERROR:
	        case E_USER_ERROR:
	            $isError = true;
	            break;
	    }
    }

    if ($isError){
		var_dump ($error);//do whatever you need with it
    }
}
//register_shutdown_function('shutdown');

?>