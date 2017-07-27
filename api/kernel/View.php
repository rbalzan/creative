<?php 

require_once PATH_API .'kernel'.DS. "ViewJSON.php";
require_once PATH_API .'kernel'.DS. "ViewXML.php";

abstract class ViewAPI {
    //Código de error
    public
		$status,
		$module,
		$statusText;
	
    public abstract function response($body, $header = array());
    
    const
		HTTP_100='Continue',
		HTTP_101='Switching Protocols',
		
		/* Especifica que un recurso o colección existe */
		HTTP_200 = array(200,'OK'),
		
		/**
		* Especifica que se creó un recurso. 
		* Se puede complementar con el header Location 
		* para especificar la URI hacia el nuevo recurso. */
		HTTP_201 = array(201, 'Created'),
		
		
		HTTP_202='Accepted',
		HTTP_203='Non-Authorative Information',
		
		/**
		* Representa un resultado exitoso pero sin retorno 
		* de algún dato (viene muy bien en DELETE)
		*/
		HTTP_204 = array(204, 'No Content'),
		
		
		HTTP_205='Reset Content',
		HTTP_206='Partial Content',
		HTTP_300='Multiple Choices',
		HTTP_301='Moved Permanently',
		HTTP_302='Found',
		HTTP_303='See Other',
		HTTP_304='Not Modified',
		HTTP_305='Use Proxy',
		HTTP_307='Temporary Redirect',
		HTTP_400='Bad Request',
		
		/**
		* Indica que el cliente debe estar autorizado primero 
		* antes de realizar operaciones con los recursos
		*/
		HTTP_401 = array(401,'Unauthorized'),
		HTTP_402='Payment Required',
		HTTP_403='Forbidden',
		
		/* Especifica que el recurso buscado no existe */
		HTTP_404 = array(404,'Not Found'),
		
		HTTP_405='Method Not Allowed',
		HTTP_406='Not Acceptable',
		HTTP_407='Proxy Authentication Required',
		HTTP_408='Request Timeout',
		HTTP_409='Conflict',
		HTTP_410='Gone',
		HTTP_411='Length Required',
		HTTP_412='Precondition Failed',
		HTTP_413='Request Entity Too Large',
		HTTP_414='Request-URI Too Long',
		HTTP_415='Unsupported Media Type',
		HTTP_416='Requested Range Not Satisfiable',
		HTTP_417='Expectation Failed',
		HTTP_500='Internal Server Error',
		HTTP_501='Not Implemented',
		HTTP_502='Bad Gateway',
		HTTP_503='Service Unavailable',
		HTTP_504='Gateway Timeout',
		HTTP_505='HTTP Version Not Supported';
}


class View {
	private 
		$_format,
		$_status,
		$_statusText,
		$_icon = 'info';
	function __construct( ) {
		
	}
	
	
	public function initialize( $status = 400 ) {
		$this->_status = $status;
	}
	
	
	/**
     * Imprime el cuerpo de la respuesta y setea el código de respuesta
     * @param mixed $body de la respuesta a enviar
     */
    public function response($status, $body){
    	
    	switch( $this->_format ){
			case 'xml':
			
				if ($this->_status) {
		            http_response_code($this->_status);
		        }
				if( !isset($body['status']) ){
					$body['status']= $this->_status;
				}
				if( !isset($body['statusText']) ){
					$body['statusText']= $this->statusText;
				}
				//$body['module'] = $this->module;				
				header('Content-Type: text/xml; charset=utf-8');
		        $xml = new SimpleXMLElement('<response/>');
		        $this->parse($body, $xml);
		        print $xml->asXML();
        
			break;
			
			default:
				$response = array();
				
				$this->_status = $status;
		        
				$response['status']= $this->_status;
				$response['statusText']= $this->_statusText ? $this->_statusText : $this->get_http_status_text($this->_status);
				$response['time'] = date('d/m/Y H:m:s');
				$response['icon'] = $this->_icon;
				
				if( $this->_status == 200 OR $this->_status == 201 ){
					http_response_code($this->_status);
				}
				
				//$response['module'] = $this->module;				
				$response = array_merge($response, $body);				
				
		        header('Content-Type: application/json; charset=utf8');
		        echo json_encode($response, JSON_PRETTY_PRINT);        
			break;
		}		
		exit;        
    }
    
    /**
	* 
	* @param undefined $data
	* @param undefined $xml_data
	* 
	* @return
	*/
    public function parse($data, &$xml_data){
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item' . $key;
                }
                $subnode = $xml_data->addChild($key);
                self::parse($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
    
    
    
    public function get_http_status_text($status){
		$http_status = array(
			100 => 'Continue',  
			101 => 'Switching Protocols',  
			200 => 'OK',
			201 => 'Created',  
			202 => 'Accepted',  
			203 => 'Non-Authoritative Information',  
			204 => 'No Content',  
			205 => 'Reset Content',  
			206 => 'Partial Content',  
			300 => 'Multiple Choices',  
			301 => 'Moved Permanently',  
			302 => 'Found',  
			303 => 'See Other',  
			304 => 'Not Modified',  
			305 => 'Use Proxy',  
			306 => '(Unused)',  
			307 => 'Temporary Redirect',  
			400 => 'Bad Request',  
			401 => 'Unauthorized',  
			402 => 'Payment Required',  
			403 => 'Forbidden',  
			404 => 'Not Found',  
			405 => 'Method Not Allowed',  
			406 => 'Not Acceptable',  
			407 => 'Proxy Authentication Required',  
			408 => 'Request Timeout',  
			409 => 'Conflict',  
			410 => 'Gone',  
			411 => 'Length Required',  
			412 => 'Precondition Failed',  
			413 => 'Request Entity Too Large',  
			414 => 'Request-URI Too Long',  
			415 => 'Unsupported Media Type',  
			416 => 'Requested Range Not Satisfiable',  
			417 => 'Expectation Failed', 
			422 => 'Unprocessable Entity',
			500 => 'Internal Server Error',  
			501 => 'Not Implemented',  
			502 => 'Bad Gateway',  
			503 => 'Service Unavailable',  
			504 => 'Gateway Timeout',  
			505 => 'HTTP Version Not Supported');
		return $http_status[$status] ? $http_status[$status] : $http_status[500];
	}
    
    
}