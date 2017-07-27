<?php

class Language {
	
	private $langs = array(
		'es'=>'Español', 'en'=>'Ingles'
	);
	
	public function load( $lang ){
		if (in_array( $lang, $this->langs)) {
			return $this->langs[$lang];
		} else {
			return 'es';
		}
	}
	
}