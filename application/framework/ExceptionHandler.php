<?php 

class ExceptionHandler extends Exception{
    public $status;
	public $code;
	
    public function __construct($status, $statusText, $debug = '')
    {
        $this->status = $status;
        $this->statusText = $statusText;
        $this->debug = $debug 
        	? $debug 
        	: array("Line: " => $this->line ,"File: " => $this->file);
    }

}

?>