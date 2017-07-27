<?php

require_once 'interfaces/IObservable.php';

class Observable implements IObservable
{
	
	private $_observers;
	
	function attach( Observable $observer ){
		$this->_observers[] = $observer;
	}
	
	function detach( Observable $observer ){
		$key = array_search($this->_observers, $observer);
		unset( $this->_observers[$key] );
	}
	
	function notify(){
		foreach($this->_observers as $observer){
			$observer->update($this);
		}
		
	}
}

?>