<?php

define( 'PATH_COMPS', 'components' . DS );
define( 'COMPS_METHOD', 'initialize' );

include_once PATH_COMPS . 'ComponentBase.php';


class Components {
	
	function __construct() {
		
	}
	
	public function render( $component, $property = array() ){
		
		//if ( is_readable(PATH_COMPS . $component . '.php') ) {
			
			include_once PATH_COMPS . $component . '.php';
			
			if ( is_callable(array($component,COMPS_METHOD)) ) {
				$component = new $component;
				return call_user_func_array(array($component,COMPS_METHOD), array($property));
			}
			
		//}
		
	}
	
	
	
}

?>