<?php


class SessionDBHandler implements SessionHandlerInterface{
    private $link;
    private $name = '';
    private $conexion;
    
    private $exists ;
    
    public function __construct(){
		$this->conexion = new Conexant();
	
		// Set handler to overide SESSION
		session_set_save_handler(
			array($this, "open"),
			array($this, "close"),
			array($this, "read"),
			array($this, "write"),
			array($this, "destroy"),
			array($this, "gc")
		);
	}
    
    /**
	* 
	* @param undefined $savePath
	* @param undefined $sessionName
	* 
	* @return
	*/
    public function open($savePath, $sessionName){
    	$this->name = $sessionName;
		$this->conexion->open(DB_USER, DB_PASSWORD, DB_HOST, DB_DATABASE);
    }
    
    /**
	* 
	* 
	* @return
	*/
    public function close(){
        $this->conexion->close();
        return true;
    }
    
    /**
	* 
	* @param undefined $id
	* 
	* @return
	*/
    public function read($id){
        $result = $this->conexion->execute("
        	SELECT `data` FROM `session` WHERE `id` = ?", array($id)
        );
        if( is_array( $result ) and count($result) ){
        	 $this->exists = TRUE;
            return $this->decode( $result[0]['data'] );
        }else{
        	$this->exists = FALSE;
            return "";
        }
    }
    
    public function write($id, $data){    	
    	$data = $this->encode($data);     
		$result = $this->conexion->execute("
        	REPLACE INTO `session` SET `id` = '".$id."', `expires` = NOW(), `data` = '".$data."'"
        );
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    public function destroy($id){
       $this->conexion->execute("DELETE FROM `session` WHERE `id` = ?", array($id));
      
       return false;
        
    }
    
    public function gc($maxlifetime) {
        $result = $this->conexion->execute("DELETE FROM `session` WHERE ((UNIX_TIMESTAMP(`expires`) + ".$maxlifetime.") < ".$maxlifetime.")");
        if($result){
            return true;
        }else{
            return false;
        }
    }
 
	public function __destruct(){
		$this->close();
		session_write_close();
	} 
  
   /**
     * Encrypt and authenticate
     *
     * @param string $data
     * @param string $key
     * @return string
     */
    private function encode($data){
    	//return $data;
		$hash = new Hash();
		return $hash->encode($data);
    }


   /**
     * Authenticate and decrypt
     *
     * @param string $data
     * @param string $key
     * @return string
     */
    private function decode($data) {
    	//return $data;
    	$hash = new Hash();
		return $hash->decode($data);

    }
    
}

/*$handler = new SessionDBHandler();
session_set_save_handler($handler, true);*/