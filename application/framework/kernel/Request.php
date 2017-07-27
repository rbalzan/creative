<?php

/**
 * 
 */
class Request {
	
	private $_modulo,
			$_lenguaje,
			$_controlador,
			$_metodo,
			$_argumentos;
			
	
	/**
	* cosntructor
	* 
	* @return
	*/
    public function __construct() {
		
		$this->parse();

		if (!$this->_controlador || $this->_controlador==NULL) {
			$this->_controlador = DEFAULT_CONTROLLER;
		}
		
		if ( !$this->_metodo) {
			$this->_metodo = DEFAULT_CONTROLLER;
		}
		
		if (!$this->_argumentos) {
			$this->_argumentos = array();
		}
		
		if (!$this->_argumentos) {
			$this->_lenguaje = DEFAULT_LANG;
		}
	}
	
	
	/**
	* Parsea una URL
	* 
	* @return
	*/
	private function parse(){		
		
		
		if( isset($_GET['url']) and $_GET['url'] ) {
			
			$url = $_GET['url'];
			
			#Convierte un Array dividido por la API cada vez que encuentre un '/'
	        $url = explode('/', $url);
	        $url = array_filter($url);
			$this->_modulo = strtolower(array_shift($url));
		
			if( !$this->_modulo ){
				#asegurar que el modulo quede en falso
				$this->_modulo = false;
				
			} else {#Si existe un módulo
			
				#Verificar que existan modulos
				if( count( Modules::get() ) ){
				
					#Verificar que el modulo esté contenido dentro de los modulos definidos
					if( !in_array($this->_modulo, Modules::get() )){
						#Si no está contenido
						$this->_controlador = $this->_modulo;
						$this->_modulo = false;
					}
					else {
						#Asignar el siguiente elemento
						$this->_controlador = strtolower(array_shift($url));
						
						#si no existe el controlador
						if( !$this->_controlador OR strpos($this->_controlador, 'tokenurl') !== FALSE ){
							#Si ya existe un modulo y no se ha enviado un controlador buscará el index
							$this->_controlador = DEFAULT_CONTROLLER;
							
						}// end if
					}// end if( !in_array
				}
				else {#Si no hay modulos definidos en la aplicación
					$this->_controlador = $this->_modulo;
					$this->_modulo = false;
				}
			}
			
	        $this->_metodo 		= strtolower(array_shift($url));
	        $this->_argumentos 	= $url; 
		}		
	}
	
	/**
	* 
	* 
	* @return
	*/
	public function get_url(){
		
		if( isset($_GET['url']) and $_GET['url'] ) {
			
			$url = $_GET['url'];
			$_url = substr($url, strlen($url)-1, 1);
			
			if( $_url =='/' ){
				$url = substr($url, 0, -1);
			}
			
			if( $url == FALSE ) $url = '/';
			
			return $url;
		}
		
		
	}
		
	/**
     * Obtiene el Módulo
     * 
     * @return
     */
    public function get_module() {
        return $this->_modulo;
    }
		
		
    /**
     * Obtiene el idioma
     * 
     * @return
     */
    public function get_lang() {
        return $this->_lenguaje;
    }

    /**
     * Obtiene el controlador
     * 
     * @return
     */
    public function get_controller() {
        return $this->_controlador;
    }

    /**
     * Obtiene el Método a utilizar
     * 
     * @return
     */
    public function get_method() {
    	return $this->_metodo;
    }

    /**
     * Obtiene los argumentos
     * 
     * @return
     */
    public function get_args() {
        return $this->_argumentos;
    }

}
?>