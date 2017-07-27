<?php

require_once 'CreativeBase.php';


/** 
 * --------------------------------------------------------------------------
 * Undocumented class
 * --------------------------------------------------------------------------
 * 
 * @author Brayan Rincon <brincon@megacreativo.com>
 */
class Creative extends CreativeBase
{
	

	/**
	 * Colección de objetos
	 * @access private
	 */
	private static $objects = array();
	
	
	/**
	 * Colección de ajustes y configuraciones
	 * @access private
	 */
	private static $settings = array();
	
	
	/**
	 * La instancia del registro
	 * @access private
	 */
	private static $instance;
	
	
	/**
	* Constructor privado para evitar que se creen directamente
	* @access private
	*/
	private function __construct(){ }		
	
	
	/**
	 * Initializes the core of the Framework
	 */
	public static function execute(){
		Router::execute( new Request() ); 
	}
	


	public static function load_config( $config ){
		if( file_exists(PATH_CONF .DS. $config.'.php') ){
			return include PATH_CONF .DS. $config.'.php';
		}
	}
	/**
	 * Asegura que solo se pueda crear una instancia de la clase y proporcionar un punto global de acceso a ella.
	 * @access public
	 * @return 
	 */
	public static function singleton(){
		if( !isset( self::$instance ) or !self::$instance instanceof self){
			$mybase = __CLASS__;
			self::$instance = new $mybase;
		}			
		return self::$instance;
	}					
	

	
	/**
	* Almacena un objeto en el registro
	* @param String $class El nombre del objeto
	* @param String $key El índice que sirve como identificador en el array	* 
	* @return void
	*/
	public static function add( $class ){
		if( $class == '' ) {
			ErrorHandler::run_exception( "Class not found" );
		}
		self::$objects[$class] = new $class( self::$instance );	
		return self::get($class);
	}	
	
	
	/**
	 * Obtiene un objeto del registro
	 * @param String $key El índice del array
	 * @return object
	 */
	public static function get( $key ){
		if( is_object(self::$objects[$key]) ){
			return self::$objects[$key];
		}
	}
	
	
	
	//------------------------------------------------------------------
	
	/**
	 * Almacena los ajustes en el registro
	 * @param String $data
	 * @param String $key El índice del array
	 * @return void
	 */
	public static function add_config( $key, $data ){
		self::$settings[$key] = $data;
	}
	
	/**
	 * Obtiene un ajuste del registro
	 * @param String $key El índice del array
	 * @return void
	 */
	public static function get_config( $Key ){
		if( isset(self::$settings[$Key]) )
			return self::$settings[$Key];
		else return FALSE;
	}
	
	/**
	 * Undocumented function
	 *
	 * @param [type] $key
	 * @return void
	 */
	public static function include_config( $key ){
		$conf = Creative::load_config( $key );
		self::$settings[$key] = $conf;
		$GLOBALS['CREATIVE']['CONF'][$key] = $conf;
		return $conf;
	}
	

	function create_core(){
		$this->add("DataBase");
	}
		
	
	//------------------------------------------------------------------
	
	/**
	* Evita la clonación del objeto
	* 
	* @return
	*/
	public function __clone(){
		trigger_error( 'Cloning of this object is not allowed', E_USER_ERROR );
	}
	
	/**
	* 
	* 
	* @return
	*/
	public function __wakeup(){
		trigger_error("No puedes deserializar una instancia de ". get_class($this) ." class.");
	}
   
    public function __sleep(){
        throw new Exception('Class  '.__CLASS__ . 'cannot be serialized');
    }
    
    /**
	*
	*/
    public static function default_template_html(){
		$content= '';
		if (file_exists( __DIR__ . '/tpl/template.tpl')){
			$file = fopen(__DIR__ . '/tpl/template.tpl', 'r');
			while(!feof($file)) {
				$content .= fgets($file);
			}
			fclose($file);
		} else {
			die( 'Error in default_template_html' );
		}

		return $content ;
	}
 

}

?>