<?php


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

include_once __DIR__ . '/ExceptionHandler.php';

abstract class ErrorHandler{

	public static function error($code, $message, $file, $line){
		
		if (!(error_reporting() & $code)) {        
			return; // Este código de error no está incluido en error_reporting
		}

		switch ($code) {
			case E_ERROR:
				echo "<strong>FATAL ERROR  [{$code}]</strong> $message<br/>\n";
				exit;
			break;
			
			case E_COMPILE_ERROR:
				echo "<strong>COMPILE ERROR  [{$code}]</strong> $message<br/>\n";
				exit;
			break;
			
			case E_PARSE:
				echo "<b>PARSE ERROR: </b> [$code] $message<br />\n";
				echo "  Error fatal en la línea $line en el archivo $file";
				echo "Abortando...<br />\n";
				exit;
				
			case E_USER_ERROR:
				echo "<b>ERROR: </b> [$code] $message<br />\n";
				echo "  Error fatal en la línea $line en el archivo $file";
				echo "Abortando...<br />\n";
				exit;
			break;
				
			case E_USER_WARNING:
				echo "<strong>WARNING[{$code}]</strong> {$message}<br/>\n";
				exit;
				break;
				
			case E_USER_NOTICE:
				echo "<strong>USER NOTICE [$code]</strong> {$message} <strong>Line:</strong> $line in {$file}<br/<br/>\n";
				exit;
			break;
			
			/**
			* Avisos en tiempo de ejecución. 
			* Indican que el script encontró algo que podría señalar un error, 
			* pero que también podría ocurrir en el curso normal al ejecutar un script.
			*/
			case E_NOTICE:
				if( ENVIRONMENT == 'development' ){
					//echo "<strong>NOTICE [$code]</strong> {$message} <strong>Line:</strong> $line in {$file}<br/>\n";
				} else {
					logs::error( "NOTICE [$code] {$message}\n" );
				}
			break;
			
			
			case E_STRICT:
				echo "<strong>STRICT ERROR  [{$code}]</strong> $message<br/>\n";
				exit;
			break;
			
        	
			
			default:
				if( ENVIRONMENT == 'development' ){
					echo "<strong>UNDEFINED ERROR [{$code}]: </strong> {$message}  <strong>Line:</strong>: $line in {$file}<br/>\n";
					exit;
				} else {
					logs::error( "UNDEFINED ERROR [{$code}]: {$message} </strong> Line: {$line} in {$file}" );
				}
			break;
				
		}

		/* No ejecutar el gestor de errores interno de PHP */
		//return true;
	}

	/**
	* Checks for a fatal error, work around for set_error_handler not working on fatal errors.
	*/
	public static function  fatal_error(){
		$error = error_get_last();
		if ( $error["type"] == E_ERROR  ){
			$message = "LINE " . $error["line"] ." IN ". $error["file"] . " | " . $error["message"] . "\n";
			logs::error( $message );
		}			
	}


	
	public static function run_exception( $exception_title, $exception_message = '' ){
		
		$template = Creative::default_template_html();	
		$file = __DIR__ . '/tpl/exception.tpl';
		$content_file = '';

		if (file_exists($file)){
			$file = fopen($file, 'r');
			while(!feof($file)) {
				$content_file .= fgets($file);
			}
			fclose($file);
		} else {
			die( $error_title );
		}

		$content_file = str_ireplace('@content',$content_file, $template);	
			
		$content_file = str_ireplace('@header',CreativeBase::get_info() . ' - <small>v'.CreativeBase::get_version().'</small>', $content_file);		
		$content_file = str_ireplace('@exception_title',$exception_title, $content_file);
		
		
		if( $exception_message != '' )
			$exception_message = '<div class="error_info"><p>' . $exception_message . '</p></div>';
			
		$content_file = str_ireplace('@exception_message',$exception_message, $content_file);
		
		
		$debug = debug_backtrace();
		$out = '';
		foreach( $debug as $key => $value){
			
			if( isset($value['line']) )
				$out .= '<strong>Line:</strong> '.$value['line'].' <strong>IN File:</strong> '.$value['file'].'<br/>';

			/*if( isset($value['file']) )
				$out .= '<strong>File:</strong> '.$value['file'].'<br/>';
*/
			if( isset($value['class']) )
				$out .= '<strong>Class:</strong> '.$value['class'].' -> ';
			
			if( isset($value['function']) )
				$out .= '<strong>Function:</strong> '.$value['function'].'<br/>';
				
			$out .= '<br/>';
		}
		if( $out != '' )
			$out = '<div class="error_info"><p>' . $out . '</p></div>';

		$content_file = str_ireplace('@calleds',$out, $content_file);
		
		throw new Exception($content_file);
	}
	
	


}


set_error_handler("ErrorHandler::error");

set_exception_handler( 	
	function ($ex){
	   	echo $ex->getMessage();
	}
);

register_shutdown_function( "ErrorHandler::fatal_error" );





?>