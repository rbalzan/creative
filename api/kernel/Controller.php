<?php

abstract class Controller {
	
	protected
		$view,
		$model_generic,
	 	$_controller,	
		$headers,
		$auth = '';
	
	public function __construct( $controller = FALSE ) {

		$this->_controller = $controller;
		$this->request = $GLOBALS['CREATIVE']['request'];
		$this->api_version = $this->request->get_version();
		$this->api_format = $this->request->get_format();
		$this->view = Creative::get( 'View' );
		$this->headers = getallheaders();
		
		if( isset($this->headers['Authorization']) ){
			 $this->headers['Authorization'];
		}
		
		$this->_put = array();
		$file= '';
		
		if( $this->request->get_request_method() == 'put' ){
			$file = file_get_contents('php://input');
			parse_str($file, $this->_put);
		}
		
		$this->app = $GLOBALS['CREATIVE']['CONF']['app'];

		$this->model_generic = NULL;
		
	}
	
	abstract public function index( );
	/*abstract public function get();
	abstract public function post();
	abstract public function put();
	abstract public function delete();*/
	


	/**
	* Cargar un Modelo
	* @param undefined $modelo Nombre del Modelo
	* @param undefined $modulo Indica si el Modelo pertenece a un Módulo
	* 
	* @return
	*/
	protected function load_model( $modelo ) {
		
		$version = $GLOBALS['CREATIVE']['request']->get_version();
		
		$modelo =  $modelo . 'Model';
		$path_model = PATH_API . $version .DS. 'models' .DS. $modelo . '.php';
		
		if (is_readable($path_model)) {
			
		  require_once $path_model;
		  $modelo = new $modelo;
		  return $modelo;
		  
		} else {
			
			$path_model =  PATH_KERNEL . 'ModelGenerator.php';
			
			if (is_readable($path_model)) {
				
				$table = str_ireplace('Model','', $modelo);
				
				$ModelGenerator = 'ModelGenerator';
				$modelo = new $ModelGenerator($table);
		  		return $modelo;
				
			} else {
				
				die($message);
				return FALSE;
			}
		}
	}
	


	/**
	* 
	* @param undefined $param
	* 
	* @return
	*/
	protected function run_method( $func, $param = NULL ){
		
		switch( $func ){
			// FIND
			case 'find':
				if( $param == NULL ){
					$this->view->response(422, 
						array(
							'statusText'=>'Param Undefined',
							'icon'=>'warning',
						)
					);
					exit;
				}
			break;
			
			
			//POST
			case 'post':
				if( $param != NULL ){			
					//$param = func_get_args();
					$method = strtolower(array_shift($param) );
								
					if( is_callable(array($this, $method)) ){
						call_user_func_array(array($this, $method),$param);
					} else {
						$this->view->response(405,
							array(
								'statusText'=>'Method Undefined or Not Allowed',
								'icon'=>'warning',
							)
						);
					}
					exit;
				}
			break;
			
			
			
			case 'put':
				if( $this->_put['id'] == '' ){
					$this->view->response(422, 
						array(
							'statusText'=>'Param ID Undefined',
							'icon'=>'warning',
						)
					);
					exit;
				}
			break;
			
			
			default:
				break;
		}
		
	}
	
	/**
	* Carga una vista
	* @param undefined $request
	* 
	* @return
	*/
	protected function load_view( $request ) {
      $this->request = $request;
      
      if (count($this->request->getArgs()) > 0) {
         
          $folder 	= strtolower($this->request->getController());
          $class 	= strtolower($this->request->getMethod());
          $args 	= $this->request->getArgs();
          $dirControl = DNA_ROOT . "controllers" . DS . $folder . DS . $class . ".php";
         
          if (is_readable($dirControl)) {
              include_once $dirControl;
              $control = new $class;
              $method = $args[0];
              if (count($args) > 1) {
                  call_user_func_array(array($control, $method), array_splice($args, 0, 1));
              } else {
                  call_user_func(array($control, $method));
              }
          } else {
              throw new Exception("Error en carga del controlador");
          }
      } else {
          $this->view->renderizar($this->request->getMethod());
      }
  }

	
	protected function response_text( $text ){
		header('Content-type: text/plain; charset=utf-8');
		echo json_encode( $text );
		exit;
	}
	
	protected function response_xml( $xml ){
		header('Content-type: text/xml; charset=utf-8');
		echo $xml;
		exit;
	}
	
	protected function response_html( $html ){
		header('Content-type: text/html; charset=utf-8');
		echo $html;
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
        	if( is_array($_POST[$clave]) ){
				return $_POST[$clave];
			} else {
				return htmlspecialchars($_POST[$clave], ENT_QUOTES);
			}
        }
        return '';
    }
    
    protected function array2str( $array ){
        return implode(',',$array);
    }
    
    /**
	* 
	* @param undefined $val
	* 
	* @return
	*/
    protected function pSQL($val, $default = NULL){
    	if( $val === NULL OR $val === '' ) return $default;
    	$val = trim($val); 
		$val = stripslashes($val); 
		$val = filter_var($val, FILTER_SANITIZE_STRING);
		return $val;
    }
    
    
    protected function get_put( $key ){
		if(isset($this->_put[$key]) && !empty($this->_put[$key])){
            return $this->_put[$key];
        }
        return '';
	}
    
     protected function get_all_put(){
     	$arr = array();
     	foreach( $this->_put as $key => $value){
		 	$arr[$key] = utf8_decode($this->_put[$value]);
		}
        return $arr;
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
    
    protected function get_file($clave){
        if( isset($_FILES[$clave]) ){
            return $_FILES[$clave];
        }
        return '';
    }
    
    /**
	* Valida si existe una sesion iniciada
	* @param undefined $ambit
	* 
	* @return
	*/
    protected function validate_auth( $ambit = BACKEND ){
		if( !Session::auth( $ambit ) ){
			$this->view->response(401, 
				array(
					'statusText'=>'Acceso restringido',
					'icon'	=>'error',
				)
			);
		}
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
    
    protected function sanitize($input, $type) {
		switch ($type) {
		// 1- Input Validation

		case 'int': // comprueba si $input es integer
			return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
		break;

		case 'string': // comprueba si $input es string
			$data = trim($input); 
			$data = stripslashes($data); 
			$data = filter_var($data, FILTER_SANITIZE_STRING);
			return $data;
		break;

		case 'digit': // comprueba si $input contiene solo numeros
		  if (ctype_digit($input)) {
		    $output = TRUE;
		  } else {
		    $output = FALSE;
		  }
		  break;

		case 'upper': // comprueba si $input contiene solo mayusculas
		  if ($input == strtoupper($input)) {
		    $output = TRUE;
		  } else {
		    $output = FALSE;
		  }
		  break;

		case 'lower': // comprueba si $input contiene solo minusculas
		  if ($input == strtolower($input)) {
		    $output = TRUE;
		  } else {
		    $output = FALSE;
		  }
		  break;
		  
		case 'email': // comprueba si $input tiene formato de email
		  $reg_exp = "/^[-.0-9A-Z]+@([-0-9A-Z]+.)+([0-9A-Z]){2,4}$/i";
		  if (preg_match($reg_exp, $input)) {
		    $output = TRUE;
		  } else {
		    $output = FALSE;
		  }
		  break;

		// 2- SQL Encoding

		case 'sql': // escapar los caracteres que no son legales en SQL

			// si magic_quotes_gpc esta activado primero aplicar stripslashes()
			// de lo contrario los datos seran escapados dos veces
			if (get_magic_quotes_gpc()) {
				$input = stripslashes($input);
			}
			// requiere una conexion MySQL, de lo contrario dara error
			return mysql_real_escape_string(trim($input));
		  break;

		// 3- Output Filtering

		case 'no_html': // los datos van a una pagina web, escapar para HTML
		  $output = htmlentities(trim($input), ENT_QUOTES);
		  break;

		case 'shell_arg': // los datos van al shell, escapar para shell argument
		  $output = escapeshellarg(trim($input));
		  break;

		case 'shell_cmd': // los datos van al shell, escapar para shell command
		  $output = escapeshellcmd(trim($input));
		  break;

		case 'url': // los datos forman parte de una URL, escapar para URL

		  // htmlentities() traduce a HTML el separador &
		  $output = htmlentities(urlencode(trim($input)));
		  break;

		case 'comment': // los datos solo pueden contener algunos tags HTML
		  $output = strip_tags($input, '<b><i><img>');
		  break;
		}
		return $output;
		}

    protected function is_email($email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }        
        return true;
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
	
	
	protected function no_cache() {
		header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
	
	
	/*****************************************************************
	* Validar la utenticidad del Token
	* @param undefined $string
	* @param undefined $keyword
	* 
	* @return
	*/
	protected function validate_token( $token, $controller='', $template = NULL ){
	
		$template = $template ? str_ireplace('@text','Error de seguridad en Token', $template) : 'Error de seguridad en Token';
		$controller != '' ? $controller : $GLOBALS['CREATIVE']['request']['controller'];
		$controller = str_ireplace('controller', '', $controller);
		
		if( ! Creative::get( 'Token' )->validar($token, $controller ) ){
			header('Content-type: application/json; charset=utf-8');
			echo json_encode(
				array(
					'status'	=> 412,
					'statusText'=> $template,
					'icon'		=> 'error',
					'token'		=> Creative::get( 'Token' )->generate($controller)					
				));
			exit;
		} else {return TRUE;}
	}
	
	
	protected function generate_token( $controller = '' ){
		$controller = str_ireplace('controller', '', $controller);
		return Creative::get( 'Token' )->generate($controller);
	}
	
	
	
	protected function basic_index(){
		
		$this->run_method('index', func_get_args());
		
		$data = $this->model_module->all();
		
		$this->view->response(200, 
			array(
				'method'	=> 'GET',
				'data'		=> $data,
				'count' 	=> count($data)
			)
		);
	}


	protected function basic_find( $id ){
		
		$this->run_method('find', $id);
		
		$this->model_module->id = $id;
		$data = $this->model_module->find();
		$this->view->response(200, 
			array(
				'method'	=> 'GET',
				'data'		=> $data,
				'count' 	=> count($data)
			)
		);
	}
	
	
	
	
	
	
	
	function check_pass($pass) {
		
		$count = strlen($pass);
		$entropia = 0;
		
		$regla = '<br/>La contraseña no cumple con los requerimientos mínimos de seguridad. Debe cumplir con lo siguiente:'.
		     		'<ul>'.
		         		'<li>Tener entre 6 y 30 caracteres alfanuméricos</li>'.
		         		'<li>Al menos una letra minúscula y una mayúscula</li>'.
		         		'<li>Contener números</li>'.
		         		'<li>Contener simbolos: @ * . !</li>'.
		     		'</ul>';
		     		
	    // Si el password tiene menos de 6 caracteres
	    if ($count==0) {
	         return array(
	         	'status' => 0,
	         	'statusText'=>
	         		'La contraseña ingresada no puede estar vacía.'. $regla);
	    }
	    if ($count < 6) {
	         return array('status'=>0,'statusText'=>'La contraseña es muy corta'. $regla);
	    }
	   
	    // Contamos cuantas mayusculas, minusculas, numeros y simbolos existen 
	    $upper = 0; $lower = 0; $numeros = 0; $otros = 0;
	    
	    for ($i = 0, $j = strlen($pass); $i < $j; $i++) {
	        $c = substr($pass,$i,1);
	        if (preg_match('/^[[:upper:]]$/',$c)) {
	            $upper++;
	        } elseif (preg_match('/^[[:lower:]]$/',$c)) {
	            $lower++;
	        } elseif (preg_match('/^[[:digit:]]$/',$c)) {
	            $numeros++;
	        } else {
	            $otros++;
	        }
	    }

	   // Calculamos la entropia
	  
		$entropia= ($upper*10) + ($lower*5) + ($numeros*5) + ($otros*15);
	  
		if ($entropia<28){
	    	return array('status'=>1,'statusText'=>'Contraseña muy debil.'. $regla);    
	    	
		}elseif($entropia<36) {
	        return array('status'=>2,'statusText'=>'Contraseña debil'. $regla); 
	        
		}elseif($entropia<60) {
			return array('status'=>3,'statusText'=>'Contraseña con seguridad media'. $regla); 
			
		}elseif($entropia<128) {
			return array('status'=>4,'statusText'=>'Contraseña fuerte');
			 
		}else {
			return array('status'=>5,'statusText'=>'Contraseña muy fuerte'); 
		}
	        
	}

}