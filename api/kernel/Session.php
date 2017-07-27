<?php

require_once 'SessionDBHandler.php';
//require_once PATH_LIBS.'Browser.php';
//require_once PATH_LIBS.'Net.php';

/**
* Tiempo de vida de la sesión en minutos
*/
define('SESSION_LIFETIME', 30);
define('SESSION_CACHE_EXPIRE', 30);

/**
* Provee una interfaz para la gestión de Sesiones y 
* control de permisos y accesos a usuarios
* 
* @package    Creativo
* @copyright  © 2004 - 2016 Brayan Rin´con
* @version    2.0.0 (Ultima revisión el 12 de noviembre de 2016)
* @author     Brayan ricnon <brayan262@gmail.com>
*/
class Session {
	
	//public static $backend = 'backend';
	//public static $frontend = 'frontend';
	
	private static $session_lifetime = SESSION_LIFETIME;
	
	/**	* 
	* @var Las páginas de sesión examinadas caducan despues de esta valor en Minutos. 
	* Predeterminado 180 minutos
	*/
	private static $session_cache_expire = 30;	
	private static $session_name = '';
	
	private static $key;
	private static $cookie;
    private static $session_hash = 'sha256';
    
    
	/**
	* Inicializar las sesiones
	* 
	* @param int $time Tiempo en segundos que durará la sesión activa. Por defecto 600 que equivale a 10 minutos
	* @return
	*/
	public static function init( $time = NULL ){
		
        //Inicializar configuración de las sesiones
		self::setup( $time );
		
		//Ejecutar un session_write_close antes de que se finalice 
		//la ejecución de PHP para evitoar errores inesperados
        register_shutdown_function('session_write_close');
        
    	session_start();
        
		session_regenerate_id(true);	
        
        
		if( isset($_SESSION['mcmark']) === false ){
			$_SESSION['mcmark'] = true;
		}
		
		//self::prevent_hijacking();
		
		if( !isset( $_SESSION['frontend'] ) ){
			$_SESSION['frontend'] = array('auth'=> FALSE);
		}
		if( !isset( $_SESSION['backend'] ) ){
			$_SESSION['backend'] = array('auth'=> FALSE);
		}
	}
	
	
	
	/**
	* Inicializa las configuraciones de las sesiones
	* 
	* @return
	*/
	private static function setup( $time = NULL ){
		
		
		self::$session_lifetime = ($time == NULL ? SESSION_LIFETIME : $time) * 60;		//El tiempo viene dado en segundos
		self::$session_name = "crtssid";	//Nombre por defecto de las sesiones
				
		
		// Check if hash is available
		if (in_array(self::$session_hash, hash_algos())) {
			ini_set('session.hash_function', self::$session_hash);
		}

		session_cache_limiter('private');
		
		ini_set('session.save_path',PATH_SESSION);
		ini_set('session.use_cookies', 1);
		ini_set('session.use_only_cookies', 1);
		ini_set('session.cookie_lifetime', self::$session_lifetime ); 		
		ini_set('session.cache_limiter', 'private');
		ini_set('session.cache_expire', 30);
		
			
		session_name(self::$session_name);	
		
		self::$key 		= 'mccookkey_';	
			
        self::$cookie 	= [];
        self::$cookie  += [
            'lifetime' => 0,
            'path'     => ini_get('session.cookie_path'),
            'domain'   => ini_get('session.cookie_domain'),
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true
        ];
        
        session_set_cookie_params(
			self::$cookie['lifetime'],
			self::$cookie['path'],
			self::$cookie['domain'],
			self::$cookie['secure'],
			self::$cookie['httponly']
        );
        
       
	}
	
	
	private static function prevent_hijacking(){
		
		if( isset($_SESSION['ipv4']) === FALSE || isset($_SESSION['user_agent']) === FALSE ){
			$_SESSION['ipv4'] = Net::ipaddress_client();
			$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			return false;
		}
		
		
		//Valdiar navegador del Cliente
		if( $_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT'] ){
			return false;
		}
		
		//Valdiar la IP del cleinte
		if( $_SESSION['ipv4'] != Net::ipaddress_client() ){
			return false;
		}
		
		return true;
	}
	
	
  /**
   * Destruye una o todas las variables de session
   * @param {String} nombre de la variable (Si no es especificado se tomara que desea eliminar todas las variables de session)
   */
	public static function destroy( $name = NULL ){
		if ($name != NULL) {
			if(is_array($name)){
                for($i = 0; $i < count($name); $i++){
                    if(isset($_SESSION[$name[$i]])){
                        unset($_SESSION[$name[$i]]);
                    }
                }
            }
            else{
                if(isset($_SESSION[$name])){
                    unset($_SESSION[$name]);
                }
            }			
	    } else {
			unset($_SESSION['mcmark']);
			@session_destroy();
			@session_unset();
	    }
	}

	/**
	* Devuelve los niveles y roles de los ambitos
	* @param undefined $ambito
	* 
	* @return
	*/
	static function get_levels( $ambito = 'frontend' ){
		return Session::$niveles[$ambito];
	}
	

	/**
	 * Retorna el valor de una variable de session
	 * @param {String} $name nombre de la variable
	 *
	 * @return {object}
	 */
	static function get( $value ){
		if(isset($_SESSION[$value]))
			return $_SESSION[$value];
	}
	
	
	/**
	 * Retorna un valor que indica si el usuario está autenticado
	 * @param {String} $name nombre de la variable
	 *
	 * @return {object}
	 */
	static function auth( $ambito = BACKEND ){
		if(isset($_SESSION[$ambito]))
			return $_SESSION[$ambito]['auth'];
	}
	
	
	/**
	 * Crea una variable de session
	 * @param {String} $name nombre de la variable
	 * @param {object} $value valor de la variable
	 */
	static function set( $name, $value ){
		if(!empty($name))
			$_SESSION[$name] = $value;
	}
	
	/**
	* 
	* @param undefined $data
	* 
	* @return
	*/
	public function crud( $data ){
		$crud = array();
		$data = explode(',',$data);
		foreach( $data as $key => $value ){
			$rol = explode(':', $value);
			$crud[$rol[0]] = $rol[1];
		}
		return $crud;
	}
	
	
	/**
	* Acceso a un Controlador
	* @param string $ambito Ambito del Acceso
	* @param string $modulo Módulo al que intenta entrar
	* @param array $niveles Niveles de acceso
	* 
	* @return
	*/
	public static function access( $ambit = 'backend', $modulo, $level = 'r' ){
    	
    	$usuario_id = Session::get($ambit)['id'];
    	self::reload_auth( $usuario_id );
    	
    	Session::time_now($ambit);
    	
    	switch( $ambit){
			case 'frontend':
				if( Session::get('frontend')['auth']==FALSE ){
           			header('location:' . BASE_URL . 'frontend/login/');
            		exit;
        		}
        		$_ambit = Session::get('frontend')['rol']['rol'];
				if(Session::get_level($level, 'frontend') > Session::get_level($_ambit, 'frontend' )){
		            header('location:' . BASE_URL . 'frontend/login/');
		            exit;
		    	}
			break;
			case 'backend':
				if( Session::get('backend')['auth']==FALSE ){
           			header('location:' . BASE_URL . 'backend/login/');
            		exit;
        		}
        		
        		$access_levels = Session::crud( Session::get('backend')['rol']['permisos'][$modulo] );
        		if( $access_levels[$level]!='1' OR $access_levels==NULL ){
					header('location: /backend/errors/400/');
            		exit;
				}
			break;
		}        
    }
    
    
    public static function access_to_action( $ambito = 'backend', $modulo, $nivel = 'R', $action = 'permisos' ){
    	
    	switch( $ambito){
			case 'frontend':
				if( Session::get($ambito)['auth']==FALSE ){
           			return FALSE;
        		}
        		$amb = Session::get($ambito)['rol']['rol'];
				if(Session::get_level($level, $ambito) > Session::get_level($amb, $ambito )){
					return FALSE;
		    	}
			break;
			case 'backend':
				if( Session::get($ambito)['auth']==FALSE ){
           			return FALSE;
        		}
        		$backend = Session::get($ambito);
        		
        		if($action == 'permisos' ){
					$niveles_accesos = Session::crud( $backend['rol'][$action][$modulo] );
	        		if( $niveles_accesos[$nivel]!='1' OR $niveles_accesos==NULL ){
						return FALSE;
					}
				}elseif($action == 'view' ){
					$niveles_accesos = $backend['rol'][$action][$modulo];
	        		if( $niveles_accesos[$nivel]!='1' OR $niveles_accesos==NULL ){
						return FALSE;
					}
				}
        		
			break;
		}
		
		return TRUE;
        
    }
    
    /**
	* Verifica si se tiene el acceso a un controlador
	* @param undefined $ambito
	* @param undefined $modulo
	* @param undefined $accion
	* @param undefined $action [access|action] access: Acceso a un módulo, action: ejecutar una operación
	* 
	* @return
	*/
    public static function access_to_controller( $ambit = 'backend', $modulo, $accion = 'r', $action = 'access' ){
    	$modulo = str_ireplace('controller','',$modulo);
    	
    	switch( $ambit){
			case 'frontend':
				if( Session::get($ambit)['auth']==FALSE ){
           			return FALSE;
        		}
        		$rol = Session::get($ambit)['rol']['rol'];
				if(Session::get_level($level, $ambit) > Session::get_level($rol, $ambit )){
					return FALSE;
		    	}
			break;
			
			case 'backend':
				if( Session::get($ambit)['auth']==FALSE ){
           			return FALSE;
        		}
        		$backend = Session::get($ambit);
        		
        		if($action == 'access' ){
					$niveles_accesos = Session::crud( $backend['rol'][$action][$modulo] );
	        		if( $niveles_accesos[$accion]!='1' OR $niveles_accesos==NULL ){
						return FALSE;
					}
				} elseif($action == 'action' ){
					$niveles_accesos = $backend['rol'][$action][$modulo];
	        		if( $niveles_accesos[$accion]!='1' OR $niveles_accesos==NULL ){
						return FALSE;
					}
				}
				
			break;
		}
		
		return TRUE;
        
    }
   
    
    
    public static function get_level($nivel, $ambito){
    	$acceso = self::$niveles[$ambito];
        if( !array_key_exists($nivel, $acceso ) ){
            throw new Exception('Error de acceso');
        } else {
        	$i = self::$niveles[$ambito][$nivel]['level'];
            return $i;
        }
    }
    
    
    
    /**
	* 
	* @param undefined $level
	* @param undefined $noAdmin
	* 
	* @return
	*/
    public static function access_estricto(array $level, $noAdmin = false){
        if(!Session::get('auth')){
            header('location:' . BASE_URL . 'cliente/acceso/login/');
            exit;
        }
        
        Session::time_now();
        
        if($noAdmin == false){
            if(Session::get('level') == 'admin'){
                return;
            }
        }
        
        if(count($level)){
            if(in_array(Session::get('level'), $level)){
                return;
            }
        }
        
		header('location:' . BASE_URL . 'cliente/acceso/login/');
    }
    
    
    
    
    public static function acceso_view_estricto(array $level, $noAdmin = false){
        if(!Session::get('auth')){
            return false;
        }
        
        if($noAdmin == false){
            if(Session::get('level') == 'admin'){
                return true;
            }
        }
        
        if(count($level)){
            if(in_array(Session::get('level'), $level)){
                return true;
            }
        }
        
        return false;
    }
    

    
    
    
    public static function time_now( $ambit = 'backend' ){
        
		if(!Session::get($ambit)['session_time'] or !self::$session_lifetime){
			Session::destroy();
            header('location:' . BASE_URL .( $ambit == 'backend' ? 'manager' : 'cliente').'/acceso/login/');
            exit();
        }
        
        if( self::$session_lifetime == 0){
            return;
        }
        
        $time = time();
        $session_time = $_SESSION[$ambit]['session_time'];
        
        if( ($time - $session_time) > self::$session_lifetime ){
            Session::destroy($ambit);
            header('location:' . BASE_URL .$ambit .'/signin/');
            exit;
        } else{
        	ini_set("session.cookie_lifetime", self::$session_lifetime );
            $_SESSION[$ambit]['session_time'] = $time;
        }
        
    }
    
    
    
	static function exist(){
		if( sizeof($_SESSION) > 0 ){
			return true;
		} else {
			return false;
		}
	}
	
	
}