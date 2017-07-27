<?php

include_once PATH_KERNEL . 'ModelGenerator.php';


/**
 * 
 * 
 * @copyright   © 2017 Brayan Rincon
 * @version     1.0.0
 * @author      Brayan Rincon <brayan262@gmail.com>
 */
abstract class Controller 
{

	private   $registry; 
    protected $view;
    protected $request;
	protected $lca;
	protected $metadata;
	protected $_callbacks_front = [];
	protected $_callbacks_back = [];
	protected $storage;
	
	
	protected $module;
	
	public function __construct( $class ) {		

		$storage 			= new \SplObjectStorage();

		$this->controller 	= $class;
		$this->request		= $GLOBALS['CREATIVE']['request'];
		$this->registry 	= Registry::get_instancia();
		$this->acl 			= new Acl();
		
		$this->view = new View( $this->request, $this->acl );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	abstract public function index();


	/**
	* 
	* @param undefined $observer
	* 
	* @return
	*/
	public function attach(\SplObserver $observer){}
    
    
    /**
	* 
	* @param undefined $observer
	* 
	* @return
	*/
    public function detach(\SplObserver $observer){ }	
    
    /**
	* 
	* 
	* @return
	*/
	public function notify(){}
	
	
	/**
	* 
	* @param undefined $position
	* @param undefined $params
	* 
	* @return
	*/
	protected function add_btn_action_datatable( $position , $params){
		$this->view->assign('action_datatable_'.$position, array(
			array(
				'color'=> $params['color'],
				'onclick' => $params['onclick'],
				'tooltip' => $params['tooltip'],
				'icon' => $params['icon'],
			)
		));
	}
	
	
	
	/**
	* Load a new module
	* @param string $model Name of the model to load
	* @param string $module Name of the module where the model is located (Optional)
	* 
	* @return
	*/
	protected function load_model( $model, $primary_key = 'id' ) {
		
		$model =  $model . 'Model';
		$path_model = PATH_APP . 'mvc' .DS. 'models' .DS. $model . '.php';
		
		if (is_readable($path_model)) {
			
		  require_once $path_model;
		  $model = new $model;
		  return $model;
		  
		} else {
			
			$path_model =  PATH_KERNEL . 'ModelGenerator.php';
			
			if (is_readable($path_model)) {
				
				$table = str_ireplace('Model','', $model);
				$ModelGenerator = 'ModelGenerator';
				$model = new $ModelGenerator($table, $primary_key);
		  		return $model;
				
			} else {
				if( ENVIRONMENT == 'development' ){
					$message = '<h3 style="text-transform: uppercase;">Error al cargar modelo</h3>';
					$message .= '<strong>Model</strong> [<span style="color:red">'.$model.'</span>]<br/>';
					$message .= '<strong>Path</strong> [<span style="color:red">'.$path_model.'</span>]';
				} else {
					$message = 'Error al cargar modelo: '.$modelo;
				}
				
				ErrorHandler::run_exception( $message );
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
    protected function load_librery($lib, $instance = FALSE) {
        $dir_lib = PATH_LIBS . $lib .DS. $lib .'.php';
        if (is_readable($dir_lib)) {
            require_once $dir_lib;
            if($instance){
            	$lib = new $lib;
            	return $lib;
            }
        } else {
            throw new Exception('Error en librería');
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
	
	
	protected function response_messages( $code ){
		switch( $code ){
			case 'reg-creado':
				return 'El registro ha sido creado exitosamente.';
				break;
				
			case 'campo-requerido':
				return 'El campo es requerido para continuar con la operación. Verifique he intete de nuevo.';
				break;
				
			case 'reg-eliminado':
				return 'El registro ha sido eliminado exitosamente..';
				break;
					
			default:
				break;
		}
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
    
    
    protected function is_email($email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        
        return true;
    }
    
    protected function get_source_view($file, $group, $ambit = BACKEND){
    	if( ! file_exists(PATH_MODULES . $ambit .DS. 'views' .DS. $group .DS. $file . '.inc.tpl') ){
            return false;
        }
        return file_get_contents( PATH_MODULES . $ambit .DS. 'views' .DS. $group .DS. $file . '.inc.tpl');
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
	
	
}