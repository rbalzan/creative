<?php

//require_once __DIR__ .DS. 'SessionDBHandler.php';
//require_once __DIR__ .DS. 'SessionFileHandler.php';
require_once PATH_FRAMEWORK .DS. 'libs' .DS. 'Browser.php';
require_once PATH_FRAMEWORK .DS. 'libs' .DS. 'Net.php';


/**
 * Provides an interface for session management
 *  
 * @package    Creative
 * @copyright  © 2017 Brayan Rincon
 * @version    2.0.0 (Ultima revisión el 24 de julio de 2017)
 * @author     Brayan Rincon <brayan262@gmail.com>
*/
class Session {

	private static 
		$key,
		$cookie,
		$conf,
		$is_initialize = false;
    

    /**
     *--------------------------------------------------------------------------
     * Session Setup
     *--------------------------------------------------------------------------
     *
     *
    */
	public function initialize(){
		
		if( self::$is_initialize == TRUE ){
			return TRUE;
		}
        //Inicializar configuración de las sesiones
		self::$conf = include PATH_CONF .DS. 'session.php';

		self::setup();

		self::prevent_hijacking();

    	session_start();
        
		session_regenerate_id(false);

		if( isset($_SESSION['CRERATIVE_MARK']) === FALSE ){
			$_SESSION['CRERATIVE_MARK'] = TRUE;
			$_SESSION['CRERATIVE_TIME'] = time();
		}
		
		if( isset($_SESSION['auth']) === FALSE ){
			$_SESSION['auth'] = FALSE;
		}

		self::$is_initialize = TRUE;

		return TRUE;
	}
	
	
	
    /**
     *--------------------------------------------------------------------------
     * Session Setup
     *--------------------------------------------------------------------------
     *
     *
    */
	private static function setup(){

		self::$key = 'creativecookkey_';	

		// Check if hash is available
		if (in_array(self::$conf['hash_function'], hash_algos())) {
			ini_set('session.hash_function', self::$conf['hash_function']);
		}

		session_cache_limiter('private');
		
		ini_set('session.save_path', self::$conf['save_path']);
		ini_set('session.use_cookies', self::$conf['use_cookies']);
		ini_set('session.use_only_cookies', 1);
		ini_set('session.cache_limiter', 'private');
		ini_set('session.cookie_lifetime', self::$conf['lifetime'] * 60 ); 	
		ini_set('session.cache_expire', self::$conf['cache_expire']);
		ini_set('session.gc_maxlifetime', 60 * 60);

		session_name(self::$conf['name']);	

        self::$cookie 	= [];
        self::$cookie  += [
            'lifetime' => ini_get('session.cookie_lifetime'),
            'path'     => ini_get('session.cookie_path'),
            'domain'   => ini_get('session.cookie_domain'),
            'secure'   => self::$conf['secure'],
            'httponly' => self::$conf['http_only']
        ];
        
        session_set_cookie_params(
			self::$cookie['lifetime'],
			self::$cookie['path'],
			self::$cookie['domain'],
			self::$cookie['secure'],
			self::$cookie['httponly']
        );


		switch ( self::$conf['driver'] ) {
			case 'file':
				include PATH_KERNEL .DS. 'sessions' .DS. 'SessionFileHandler.php';
				$session_handler = new SessionFileHandler();
			break;
			case 'cookie':
				# code...
			break;

			case 'database':
				include PATH_KERNEL .DS. 'sessions' .DS. 'SessionDBHandler.php';
				$session_handler = new SessionDBHandler( self::$conf['table'] );
			break;
			
			//file
			default: 
				exit('Driver Session not support');
			break;
		}

	}
	
	/**
	*
	*/
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
			unset($_SESSION['CRERATIVE_MARK']);
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
	public static function get_levels( $ambito = 'frontend' ){
		return Session::$niveles[$ambito];
	}
	

	/**
	 * Retorna el valor de una variable de session
	 * @param {String} $name nombre de la variable
	 *
	 * @return {object}
	 */
	public static function get( $value ){
		if(isset($_SESSION[$value])){
			return $_SESSION[$value];
		}
	}
	
	
	/**
	 * Retorna un valor que indica si el usuario está autenticado
	 * @param {String} $name nombre de la variable
	 *
	 * @return {object}
	 */
	public static function auth( $ambito ){
		if(isset($_SESSION[$ambito]))
			return $_SESSION[$ambito]['auth'];
	}
	
	
	/**
	 * Crea una variable de session
	 * @param {String} $name nombre de la variable
	 * @param {object} $value valor de la variable
	 */
	public static function set( $name, $value ){
		if(!empty($name))
			$_SESSION[$name] = $value;
	}


	/**
	 * Undocumented function
	 *
	 * @param string $ambit
	 * @return void
	 */
    public static function time_now( $ambit = 'backend' ){
        
		if( !Session::get($ambit)['session_time'] or !self::$session_lifetime ){
			Session::destroy();
            header('location: ' . '/accounts/auth/');
            exit();
        }
        
        if( self::$session_lifetime == 0){
            return;
        }
        
        $time = time();
        $session_time = $_SESSION[$ambit]['session_time'];
        
        if( ($time - $session_time) > self::$session_lifetime ){
            Session::destroy($ambit);
            header('location :' . '/accounts/auth/');
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