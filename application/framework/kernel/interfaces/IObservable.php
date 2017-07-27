<?php

interface IObservable{
	function attach( Observable $observer );
	function detach( Observable $observer );
	function update( Observable $observer );
	function notify();
}

?>