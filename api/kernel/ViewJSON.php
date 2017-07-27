<?php

class ViewJSON extends ViewAPI {
	
    public function __construct( ){
    	return $this;
    }
	
	/**
	* 
	* @param undefined $module
	* @param undefined $status
	* 
	* @return
	*/
	public function initialize($status = 400){
        $this->status = $status;
        //$this->module = str_ireplace('controller','', $module);
        
	}


    /**
     * Imprime el cuerpo de la respuesta y setea el código de respuesta
     * @param mixed $body de la respuesta a enviar
     */
    public function response($body, $header = array()){
		$response = array();
		
		$response['status']= $this->status;
		$response['statusText']= $this->statusText;
		//$response['module'] = $this->module;
		
		$response = array_merge($response, $body, $header);
		
		if ($this->status) {
			http_response_code($this->status);
		}
		
		
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
}



?>