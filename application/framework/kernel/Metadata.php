<?php

class Metadata {
	
	private $_url 		= '';
	private $_metadatos = array();
	private $_page 	= NULL;
	private $_conex		= NULL;

	function __construct( $url = '' ) {
		
		if( $url != '' ) $this->initialize($url);
		
	}
	
	public function initialize( $url ){
		
		if( isset($_GET['url']) and $_GET['url'] ){
		
			$this->_url = $_GET['url'];
			$len = strripos($this->_url, '/');		
			$result = substr($this->_url, strlen($this->_url)-1, 1);
			
			if( $result =='/' ){
				$this->_url = substr($this->_url, 0, -1);
			}
			
			if( $this->_url == FALSE ) $this->_url = '/';
			
			try {
			 	Creative::get( 'Conexant' )->open( DB_USER, DB_PASSWORD, DB_HOST, DB_DATABASE );
			} catch (Exception $ex) {
				
			}
			
			$pagina =  Creative::get( 'Conexant' )->execute("SELECT * FROM pages WHERE url = ? LIMIT 1", array($this->_url));			
			if( count($pagina) ){
				$this->_page = $pagina[0];
			}
			
			$metadatos = Creative::get( 'Conexant' )->execute("SELECT * FROM pages_meta WHERE page_id = ?", array($this->_page['id']));			
			
			if( is_array($metadatos) and count($metadatos) ){
				if( $metadatos[1] !=NULL ){
					foreach( $metadatos  as $key => $value){
						$this->_metadatos[$value['name']] = array(
							'content'=>$value['content'],
							'attr'=>$value['attr']
						);
					}
				}
			}
				
			
		}
		
	}
	
	public function pagina(){
		return $this->_page;
	}
	
	
	/**
	* Obtiene el título de la página
	* 
	* @return
	*/
	public function titulo(){
		return $this->_get_pagina('titulo');
	}


	/**
	* Obtiene el Meta Titulo de la Página con el formato
	* TITULO + SEPARADOR + SITE_NAME
	* 
	* @return
	*/	
	public function title(){
		$title = trim( $this->titulo());
		if( $title=='' ){
			return SITE_NAME;
		} else {
			return  $title.TITLE_SEP.SITE_NAME;
		}		
	}
		
	/**
	* Obtiene el Sub-Titulo de la página
	* 
	* @return
	*/
	public function subtitle(){
		return $this->_get_metadato('subtitle');
	}
	
	/**
	* Obtiene las Meta Keywords de la página
	* 
	* @return
	*/
	public function keywords(){
		return $this->_get_pagina('metakeyword');
	}
	
	/**
	* 
	* Obtiene las Meta Description de la página
	* @return
	*/
	public function description(){
		return $this->_get_pagina('metadescription');
	}
	
	/**
	* Obtiene el nombre del Sitio
	* 
	* @return
	*/
	public function site_name(){
		return SITE_NAME;
	}
	
	
	private function _get_pagina( $campo ){
		return isset($this->_page[$campo]) ? $this->_page[$campo] : '';
	}
	
	
	private function _get_metadato( $campo ){
		return isset($this->_metadatos[$campo]['content']) ? $this->_metadatos[$campo]['content'] : '';
	}
	
	
}