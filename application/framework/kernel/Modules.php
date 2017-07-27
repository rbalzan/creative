<?php

abstract class Modules{
	
	public static function get(){
		return array(
			'backend',
			'api',
			'frontend',
		);
	}
}