<?php


define('API_CONTENT_TYPE', 'json' );

// Constantes de estado
const STATUS_URL_INCORRECTA = 2;
const STATUS_EXISTENCIA_RECURSO = 3;
const STATUS_METODO_NO_PERMITIDO = 4;

/*
200	OK	Úsalo para especificar que un recurso o colección existe
201	Created	Puedes usarlo para especificar que se creó un recurso. Se puede complementar con el header Location para especificar la URI hacia el nuevo recurso.
204	No Content	Representa un resultado exitoso pero sin retorno de algún dato (viene muy bien en DELETE).
304	No Modified	Indica que un recurso o colección no ha cambiado.
401	Unauthorized	Indica que el cliente debe estar autorizado primero antes de realizar operaciones con los recursos
404	Not Found	Ideal para especificar que el recurso buscado no existe
405	Method Not Allowed	Nos permite expresar que el método relacionado a la url no es permitido en la api
422	Unprocessable Entity	Va muy bien cuando los parámetros que se necesitaban en la petición no están completos o su sintaxis es la incorrecta para procesar la petición.
429	Too Many Requests	Se usa para expresarle al usuario que ha excedido su número de peticiones si es que existe una política de límites.
500	Internal Server Error	Te permite expresar que existe un error interno del servidor.
503	Service Unavailable	Este código se usa cuando el servidor esta temporalmente fuera de servicio.
*/

abstract class Router {
	
	private $content_type = [
		'json'	=>'application/json; charset=utf-8',
		'xml'	=>'text/xml; charset=utf-8',
	];
	
	
	public static function execute() {  
		
		//Setear la URL
		$request = new Request();
		
		//OPbtener el Controlador a procesar
		$controller = $request->get_controller();
		$method		= $request->get_method();
		$args 		= $request->get_args();
		$version	= $request->get_version();
		$format		= $request->get_format();
		
		$GLOBALS['CREATIVE']['request'] = $request;
		
		//diorectoriuo del Controlador
		$path_controller = PATH_VERSIONS . $version .DS. 'controllers' .DS . $controller . 'Controller.php';
		
		//Veirficar si el controaldor existe
		if ( is_readable($path_controller) ){
			require_once $path_controller;
			$controller = str_ireplace("-","_", str_ireplace(".","__", $controller)).'Controller';
			$controller = new $controller;
		} else {
			throw new ErrorHandler(404,'Controller Not Found', 'Controller Not Found: versions' .DS. $version .DS. 'controllers' .DS . $controller . 'Controller.php');
		}
		
		
		//$method = strtoupper($_SERVER['REQUEST_METHOD']);
		if( $method == 'all' ) $method = 'all';
		if ( !is_callable(array($controller,$method)) ) {
			$args[] = $method;
			$method = 'index';	
		}
		
		
		//Si hay argumentos
		if ( isset($args) ) {
			//enviar nombre de la clase, el método y los argumentos a el método que se esta llamando
			call_user_func_array(array($controller,$method),$args);
		}
		else {
			call_user_func(array($controller,$method));
		}//END IF
		
		
		
		
		
		
		
		
		
		
		exit;
		switch ($method) {
			
			case 'GET'://consultar
				echo 'GET';
			break;
			
			case 'POST'://insertar
				echo 'POST';
			break; 
			
			case 'PUT'://actualizar
				echo 'PUT';
			break; 
			
			case 'DELETE'://eliminar
				echo 'DELETE';
			break;
			
			default://metodo NO soportado
				echo 'METODO NO SOPORTADO';
			break;
		}
	}

	private function clean($data) {  
		$entrada = array();  
		if (is_array($data)) {
			foreach ($data as $key => $value) {  
				$entrada[$key] = $this->clean($value);  
			}  
		} else {  
			if (get_magic_quotes_gpc()) {
				//Quitamos las barras de un string con comillas escapadas  
				//Aunque actualmente se desaconseja su uso, muchos servidores tienen activada la extensión magic_quotes_gpc.   
				//Cuando esta extensión está activada, PHP añade automáticamente caracteres de escape (\) delante de las comillas que se escriban en un campo de formulario.   
				$data = trim(stripslashes($data));  
			}  
			//eliminamos etiquetas html y php  
			$data = strip_tags($data);  
			//Conviertimos todos los caracteres aplicables a entidades HTML  
			$data = htmlentities($data);  
			$entrada = trim($data);  
		}  
		return $entrada;  
	}
   
	private function get_codes_responses( $code ) { 
		$status = array(  
			200 => 'OK',  
			201 => 'Created',  
			202 => 'Accepted',  
			204 => 'No Content',  
			301 => 'Moved Permanently',  
			302 => 'Found',  
			303 => 'See Other',  
			304 => 'Not Modified',  
			400 => 'Bad Request',  
			401 => 'Unauthorized',  
			403 => 'Forbidden',  
			404 => 'Not Found',  
			405 => 'Method Not Allowed',  
			500 => 'Internal Server Error'
		);  
		return $status[$code]; 
		
	} 
   
}//end class
