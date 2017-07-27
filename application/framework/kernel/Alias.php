<?php


class Alias {
	
	public $controllers;
	public $methods;
	
	public function __construct(){
		$this->controllers['index'] = array(
			'home', 'inicio'
		);
	}
	
	public function getController( $alias ){
		foreach( $this->controllers as $arr_alias){
			
			if( isset($this->controllers[$alias]) ){
				return $alias;
			}
			
			foreach( $arr_alias as $al){
				if( $alias === $al ){
					$arr = array_search( $arr_alias, $this->controllers );
					return $arr;
				}				
			}//foreach
		}//foreach
		
		return '_404_';
	}
	
}