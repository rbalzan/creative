<?php

class Generador {
	private $_controller;
	private $_conex;
	private $_response;
	
	public function __construct( $controller = FALSE ) {
		$this->_conex = new Conexant();
		$this->_controller = $controller;
		
	}
	
	public function exists(){
		try {
		 	$this->_conex->open( DB_USER, DB_PASSWORD, DB_HOST, DB_DATABASE );
		 	$rows = $this->_conex->execute('
				SELECT
					count(*) n
				FROM paginas
					WHERE url = ?',
			array($this->_controller));
			if( count($rows) ){
				$rows = $rows[0];
				if( isset($rows['n']) ){
					return (int)$rows['n']>0 ? TRUE : FALSE;
				} else {
					return FALSE;
				}
			} else return FALSE;
			
		} catch (Exception $ex) {
			return FALSE;
		}
		
	}
	
	
	public function obtener(){
		try {
		 	$this->_conex->open( DB_USER, DB_PASSWORD, DB_HOST, DB_DATABASE );
		} catch (Exception $ex) {
			return FALSE;
		}
		
		$rows = $this->_conex->execute('
			SELECT
				*
			FROM paginas
				WHERE url = ? AND estatus = 1 AND version_estatus = 1
				ORDER BY creado DESC
			LIMIT 1',
		array($this->_controller));
		$rows = $rows[0];
		
		$this->_response = $rows;
		
		return $this->_response;
		
	}	

	
	public function get_html(){
		return html_entity_decode($this->_response['contenido']);
	}
	
	public function get_page(){
		return $this->_response;
	}


}

?>