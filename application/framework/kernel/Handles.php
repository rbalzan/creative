<?php


abstract class Handles {

	private   $registry; 
    protected $view;
    protected $request;
	protected $lca;
	protected $metadata;
	protected $_callbacks_front = [];
	protected $_callbacks_back = [];
	
	public function __construct(  ) {
         
	}

	
	
	/**
	* Cargar un Modelo
	* @param undefined $modelo Nombre del Modelo
	* @param undefined $modulo Indica si el Modelo pertenece a un Módulo
	* 
	* @return
	*/
	protected function load_model( $modelo, $modulo = FALSE ) {
		
		$modelo =  $modelo . 'Model';
		$dirModelo = PATH_CORE . 'models' . DS . $modelo . '.php';
		
		if (is_readable($dirModelo)) {
			
		  require_once $dirModelo;
		  $modelo = new $modelo;
		  return $modelo;
		  
		} else {
			
			$dirModelo =  PATH_APP . 'ModelGenerator.php';
			
			if (is_readable($dirModelo)) {
				
				$table = str_ireplace('Model','', $modelo);
				$ModelGenerator = 'ModelGenerator';
				$modelo = new $ModelGenerator($table);
		  		return $modelo;
				
			} else {
				if( ENVIRONMENT == 'development' ){
					CreativoBase::get_info();
					$message = '<h2 style="text-transform: uppercase;">Error al cargar modelo</h2>';
					$message .= '<strong>Modelo:</strong> <strong style="color:#9F606D">'.$modelo.'</strong>';
				} else {
					$message = 'Error al cargar modelo: '.$modelo;
				}
				
				log::error('Error al cargar modelo: '.$modelo);
				
				die($message);
				return FALSE;
			}
		}
	}


	/**
	* Carga una librería
	* @param undefined $libreria
	* @param undefined $subdir
	* 
	* @return
	**/
    protected function load_librery($libreria, $subdir = FALSE, $instance = FALSE) {
        $dir_lib = PATH_LIBS . ($subdir ? $subdir .DS. $libreria : $libreria) . '.php';
        if (is_readable($dir_lib)) {
            require_once $dir_lib;
            if($instance){
            	$libreria = new $libreria;
            	return $libreria;
            }
        } else {
            throw new Exception('Error en librería');
        }
    }
    
    
    
	protected function response_text( $json ){
		header('Content-type: application/json; charset=utf-8');
		echo json_encode( $json );
		exit;
	}
	
	protected function response_xml( $json ){
		header('Content-type: application/json; charset=utf-8');
		echo json_encode( $json );
		exit;
	}
	
	protected function response_html( $json ){
		header('Content-type: application/json; charset=utf-8');
		echo json_encode( $json );
		exit;
	}
	
	protected function response_json( $json ){
		header('Content-type: application/json; charset=utf-8');
		echo json_encode( $json );
		exit;
	}
	
		
	/**
	* Si no existe la variable POST se devuelve una cadena vacía '', 
	* y Convierte caracteres especiales en entidades HTML 
	* @param undefined $clave
	* 
	* @return
	*/
    protected function get_string($clave){
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES);
            return $_POST[$clave];
        }
        return '';
    }
    
    /**
	* Si no existe la variable POST se devuelve una cadena vacía ''
	* @param undefined $clave
	* 
	* @return
	*/
    protected function get_post($clave){
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            return $_POST[$clave];
        }
        return '';
    }
    
    
    protected function get_float($clave){
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            return (float)$_POST[$clave];
        }
        return 0.0;
    }
    
    
    protected function get_int($clave){
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
            return $_POST[$clave];
        }
        return 0;
    }
    
    
    /**
	* Obteiene el valor alfanumerico de una variable POST,
	* @param undefined $clave
	* 
	* @return
	*/
    protected function get_alphanum($clave){
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = (string) preg_replace('/[^a-zA-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
        }
        return '';
    }
    
        
	protected function location($ruta = FALSE,$externa = FALSE){
   		if( $externa ){
			header('Location: ' . $ruta);
			exit;
		}
		
        if($ruta){
            header('Location: ' . BASE_URL . $ruta);
            exit;
        } else{
            header('Location: ' . BASE_URL);
            exit;
        }
    }
    
            /********************************************
	 * Codificar un Estring
	 * @param undefined $string
	 * 
	 * @return
	 */
	protected function encode( $string, $hash ){
    	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($hash), $string, MCRYPT_MODE_CBC, md5(md5($hash))));
	}
	
	
	/********************************************
	 * Decodificar un String
	 * @param undefined $string
	 * 
	 * @return
	 */
	protected function decode( $string, $hash ){
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($hash), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($hash))), "\0");
	}
	
	
}