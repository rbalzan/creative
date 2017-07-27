<?php


require_once PATH_KERNEL . 'HttpStatus.php';

/**
* Enruta las peticiones HTTP
* 
* @package	Creativo
* @author Brayan Rincon
* @since	Version 4.0.0
*/


class Router {

		
	public static $resquest;
	
	
	/**
	* Inicia el proceso de enrutamiento
	* @param undefined $peticion
	* 
	* @return
	*/
	public static function execute( Request $request ) {
		
    	$module    			= $request->get_module();
		$controller    		= $request->get_controller();
		$controller_format	= str_ireplace("-","_", str_ireplace(".","__", $controller));
		$path_controller 	= PATH_CONTROLLERS . $controller_format .'Controller.php';
		
		$method         = $request->get_method();
		$argumentos     = $request->get_args();
		$lenguaje		= $request->get_lang();
		
		$GLOBALS['CREATIVE']['request'] = $request;
		
		#Verificar si existe un módulo
		if( $module ){
			
			/*if( $module===DEFAULT_MANAGER ){
				
				$path_controller = PATH_MANAGER_CONTROLLERS . $controller_format .'Controller.php';
			} else {*/
			
				$module = str_ireplace("-","_", $module);
				$module = str_ireplace(".","__", $module);
				
				
				#revisa si hay un controlador base del modulo
				#El proposito del controlador base es que proporcione codigo para el modulo completo
				$dir_module =  PATH_CONTROLLERS . $module .'Controller.php';
				
				#se carga el controlador base
				if ( is_readable($dir_module) ) {
									
					require_once $dir_module;
					$controller = str_ireplace("-","_", str_ireplace(".","__", $controller));
					#si trabajamos en base a un modulo, tiene que adquirir los controladores dentro de la carpeta del modulo
					$path_controller = PATH_APP.'modules' .DS. $module .DS. 'controllers' .DS. $controller .'Controller.php';
				}
				else{
					$out = '<strong>Directory Module: </strong>'. $dir_module ;
					ErrorHandler::run_exception('Module not found', $out);
				}
			
			/*}*/
			
		}
		else{						
			#si no se esta trabajando en base a un modulo
			$path_controller = PATH_CONTROLLERS . $controller_format .'Controller.php';
			//define('THEMES_ACTIVE', DEFAULT_THEME);
		}
				
		
    	//verificar si el archivo existe y es válido es decir legible
		if ( is_readable($path_controller) ) {

			require_once $path_controller;


			$controller = str_ireplace("-","_", str_ireplace(".","__", $controller)).'Controller';

			if( class_exists($controller) !== FALSE ){
				$controller = new $controller;
			} else {
				ErrorHandler::run_exception( 'Error in Controller: [' . $controller. ']' );
			}


			$method = str_ireplace("-","_", str_ireplace(".","__", $method));
			

			if ( is_callable(array($controller,$method)) ) {
				$method = $request->get_method();
			} else {
				if( REDIRECT_DEFAULT_METHOD == TRUE ) {
					$argumentos[] = $method;
					$method = 'index';	
				} else {
					self::render_http_status( HttpStatus::HTTP_404 );
				}
			}
			

			if ( is_callable(array($controller,$method)) ) {
				$method = $request->get_method();
			} else {
				$argumentos[] = $method;
				$method = 'index';				
			}
			
			
			//Si hay argumentos
			if ( isset($argumentos) ) {
				//enviar nombre de la clase, el método y los argumentos a el método que se esta llamando
				call_user_func_array(array($controller,$method),$argumentos);
			}
			else {
				call_user_func(array($controller,$method));
			}//END IF

		} else {
			
			if( USE_GENERATOR ){
				$generador = new Generador( $controller );
	      		if( $generador->exists() === TRUE ){
	      			
					$page = $generador->obtener();
					$page = $page;
					
					header('Content-Type: text/html; charset=utf-8');
					$html = $generador->get_html();
									
					$view = new View( $request );
					$view->generate( $generador->get_page() );
					
					//echo $html;					
				}
			} else {
				self::render_http_status( HttpStatus::HTTP_404 );
			}
		}			
	}


	public static function render_http_status( $status = HttpStatus::HTTP_404 ){
		header("HTTP/1.0 {$status['status']} " . $status['statusText']);
		header("Status: {$status['status']} " . $status['statusText'] );
		
		$view = new View( $GLOBALS['CREATIVE']['request'] , new Acl() );
		$view->theme( BACKEND );
		$view->render_error($status['status']);
		exit;
	}
	
		
}
