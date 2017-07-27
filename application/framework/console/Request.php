<?php

/**
 * 
 */
class Request {
	
	private $_command;
	/**
	* cosntructor
	* 
	* @return
	*/
    public function __construct() {
		
		$args = $GLOBALS['CREATIVE']['CLI']['ARGS'];
        $args = array_filter($args);

		$this->_command	= strtolower(array_shift($args));
        $this->_command	= strtolower(array_shift($args));

        /*if( strpos( $this->_method, ':') ){

            $exp = explode( ':', $this->_method);
            $this->_method  = $exp[0];
            $this->_args 	= array_merge(array($exp[1]), $args);   
           
        } else {
            $this->_args 	= $args;   
        }*/

              
	}

    /**
    * Obtiene el Método a utilizar
    * 
    * @return
    */
    public function get_command() {
    	return $this->_command;
    }


}
?>