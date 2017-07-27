<?php

/**
 * 
 */
class Request {
	
	private $_version,
			$_controller,
			$_format,
			$_method,
			$_args = array();
			
	
	/**
	* Constructor
	* 
	* @return
	*/
    public function __construct() {
		
		$this->parser();

		if (!$this->_controller || $this->_controller==NULL) {
			$this->_controller = DEFAULT_CONTROLLER;
		}
		
		if ( !$this->_method) {
			$this->_method = DEFAULT_CONTROLLER;
		}
		
		if (!$this->_args) {
			$this->_args = array();
		}
	}
	
	
	/**
	* Parsea una URL
	* 
	* @return
	*/
	private function parser(){
		if( isset($_GET['url']) and $_GET['url'] ) {
			
			$url = $_GET['url'];
			
			#Convierte un Array dividido por la API cada vez que encuentre un '/'
	        $url = explode('/', $url);
	        $url = array_filter($url);
			
			$this->_resquest_method = strtolower($_SERVER['REQUEST_METHOD']);
			$this->_version 		= strtolower(array_shift($url));
	        $this->_controller		= strtolower(array_shift($url));
	        $this->_method			= strtolower(array_shift($url));
	        $this->_args 			= $url;
	  		$this->_format			= DEFAULT_FORMAT;
	        

			if( $this->_controller == '' ) {
				$this->_controller = DEFAULT_CONTROLLER;
			} else {
 				if( strpos( $this->_controller, '.' ) ){
					$this->_controller = explode('.',$this->_controller)[0];
					$this->_format = explode('.',$_GET['url'])[1];
					//Corregir entrada del Formato
				}
			}

	       
			
			switch( $this->_resquest_method ){
				case 'get':
					if( $this->_method == ''){
						$this->_method = DEFAULT_METHOD;
					} else {
						//Asegura que solo se ejecuten esos tres tipos de metodos
						if( $this->_method != 'find' AND $this->_method != 'search' AND $this->_method != 'all' ){
							array_unshift($this->_args,  $this->_method );
							$this->_method 	= 'all';
						}
					}
					
				break;
				
				case 'post'://insertar
					if( !empty($this->_method) ){
						array_unshift($this->_args,  $this->_method );
					}
					$this->_method = 'post';
				break; 
				
				case 'put'://actualizar
					$this->_method = 'put';
				break; 
				
				case 'delete'://eliminar
					if( !empty($this->_method) ){
						array_unshift($this->_args,  $this->_method );
					}
					$this->_method = 'delete';
				break;
				
				default://metodo NO soportado
					$this->_method = DEFAULT_METHOD ;
					$this->_args['resquest_method']	= $this->_resquest_method;
				break;
			}
	        
		} else {
			$response = array(
				'status'=>200, 
				'statusText'=>'Ok',
				'time'=>time()
			);			
			http_response_code(200);
	        header('Content-Type: application/json; charset=utf8');
	        echo json_encode($response, JSON_PRETTY_PRINT);
	        exit;
		}
	}
	
		
    /**
     * Obtiene el controlador
     * 
     * @return
     */
    public function get_controller() {
        return $this->_controller;
    }

    /**
     * Obtiene el Método a utilizar
     * 
     * @return
     */
    public function get_method() {
    	return $this->_method;
    }

    /**
     * Obtiene los argumentos
     * 
     * @return
     */
    public function get_args() {
        return $this->_args;
    }
    
    
    public function get_request_method(){
		return $this->_resquest_method;
	}
    
	public function get_version(){
		return $this->_version;
	}
	
	public function get_format(){
		return $this->_format;
	}
	
}
?>