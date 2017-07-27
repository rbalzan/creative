<?php

class Router {
	
	public static function execute( $request ) {

		$command = $request->get_command().'Command';
		//$args = $request->get_args();
		//$method = $request->get_method();
		//;
		$path_command = __DIR__ . '/commands/'.$command.'.php';
		
		if ( is_readable($path_command) ) {
			require_once $path_command;
			//$method = new $method;
			//call_user_func_array(array($method,'run'), $args);
		}

		return $command;
	}
	
}

?>