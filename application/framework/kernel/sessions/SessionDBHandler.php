<?php


class SessionDBHandler implements SessionHandlerInterface
{
    private $link;
    private $name = '';
    private $conexion;
    private $table;
    private $exists ;
    
    public function __construct($table){
        $this->table = $table;
		
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
     *--------------------------------------------------------------------------
     * Session Open
     *--------------------------------------------------------------------------
	 *
	 * @param   string  $save_path  The path where to store/retrieve the session.
	 * @param   string  $session_name The session id.
	 * 
	 * @return bool The return value (usually TRUE on success, FALSE on failure).
	 *                Note this value is returned internally to PHP for processing.
	 */
    public function open($save_path, $session_name){
        $this->life_time = get_cfg_var("session.gc_maxlifetime"); 
    	$this->name = $save_path;
		Creative::get( 'Conexant' )->open(DB_USER, DB_PASSWORD, DB_DATABASE, DB_HOST);
        return TRUE;
    }
    

    /**
     *--------------------------------------------------------------------------
     * Session Close
     *--------------------------------------------------------------------------
	 *
	 * @return bool The return value (usually TRUE on success, FALSE on failure).
	 *              Note this value is returned internally to PHP for processing.
	 */
    public function close(){
        $this->gc(ini_get('session.gc_maxlifetime')); 
        return true;
    }
    


    /**
     *--------------------------------------------------------------------------
     * Session Read
     *--------------------------------------------------------------------------
	 *
     * @param  string  $id The session id to read data for.
	 * @return bool The return value (usually TRUE on success, FALSE on failure).
	 *              Note this value is returned internally to PHP for processing.
	 */
    public function read($id){
        $result = Creative::get( 'Conexant' )->execute("SELECT data FROM {$this->table} WHERE id = ?", array($id)
        );
        if( is_array( $result ) AND count($result) ){
        	 $this->exists = TRUE;
            return  Creative::get( 'Hash' )->decode( $result[0]['data'] );
        }else{
        	$this->exists = FALSE;
            return "";
        }
    }
    

    /**
     *--------------------------------------------------------------------------
     * Session write
     *--------------------------------------------------------------------------
	 *
	 * @param   string  $id    The session id.
	 * @param   string  $data  The encoded session data. This data is the
	 *                         result of the PHP internally encoding
	 *                         the $_SESSION super global to a serialized
	 *                         string and passing it as this parameter.
	 *                         Please note sessions use an alternative serialization method.
     * 
	 * @return bool The return value (usually TRUE on success, FALSE on failure).
	 *              Note this value is returned internally to PHP for processing.
	 */
    public function write($id, $data){

        $new_exp = time() + $this->life_time;
    	$data = Creative::get( 'Hash' )->encode( $data );    

        $result = Creative::get( 'Conexant' )->row(
            "SELECT data 
            FROM {$this->table}  
            WHERE id = '{$id}' AND expire > ".time());

        if( is_array( $result ) AND count($result)<1 ){
            $result = Creative::get( 'Conexant' )->execute(
                "INSERT INTO {$this->table} (id, expire, data, user_agent, ip_public) VALUES ( '{$id}', {$new_exp}, '{$data}', '{$_SERVER['HTTP_USER_AGENT']}', '{$_SERVER['REMOTE_ADDR']}' )"
            );
           } else {
            $result = Creative::get( 'Conexant' )->execute(
                "UPDATE {$this->table} SET 
                    expire = {$new_exp}, 
                    data = '{$data}', 
                    user_agent = '{$_SERVER['HTTP_USER_AGENT']}', 
                    ip_public = '{$_SERVER['REMOTE_ADDR']}'
                WHERE id = '{$id}'"
            );
        }
        return true;
    }


    /**
     *--------------------------------------------------------------------------
     * Destroy Session
     *--------------------------------------------------------------------------
	 *
	 * @param int  $id  The session ID being destroyed.
	 * @return bool The return value (usually TRUE on success, FALSE on failure).
	 *              Note this value is returned internally to PHP for processing.
	 */    
    public function destroy($id){
       Creative::get( 'Conexant' )->execute("DELETE FROM {$this->table} WHERE id = ?", array($id));      
       return false;
        
    }
    
    public function gc($maxlifetime) {
        $result = Creative::get( 'Conexant' )->execute("DELETE FROM {$this->table} WHERE expire < {$maxlifetime}");
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
    
}