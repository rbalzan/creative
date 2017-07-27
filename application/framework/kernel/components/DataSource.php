<?php

class DataSource {
	
	private $_datasources = array();
	
	
	public function initialize(){
		return $this;
	}
	/**
	* Crea un nuevo Data Soruce
	* @param undefined $name Nombre del DataSource
	* @param undefined $property Propiedades de DAtaSource
	* 
	* @return
	*/
	public function create( $name, $property ){
		if( isset($this->_datasources[$name]) ){
			return $this->_datasources[$name];
		} else {
			$dsi = new DataSourceItem();
			$dsi->create($name, $property);
			$this->_datasources[$name] = $dsi;
			return $dsi;
		}
	}
	
	
	/**
	* Devuelve el DataSource asociado al nombre
	* @param undefined $name
	* 
	* @return
	*/
	public function get_datasource( $name ){
		if( isset($this->_datasources[$name]) ){
			return $this->_datasources[$name];
		} else return NULL;
		
	}
	
	
	/**
	* Devuelve el DataSource asociado al nombre
	* @param undefined $name
	* 
	* @return
	*/
	public function get_datails( $name ){
		if( isset($this->_datasources[$name]) ){
			return array( 'name' => $name, 'datasources'=>$this->_datasources[$name]);
		} else return NULL;
		
	}
	
	
	/**
	* 
	* @param undefined $name
	* 
	* @return
	*/
	public function get_data( $name ){
		
		if( isset($this->_datasources[$name]) ){
			
			$ds = $this->_datasources[$name];
			$data = array();
			
			foreach( $ds->source->all(array(), array($ds->value => 'DESC')) as $key => $value){
				$data[$value[$ds->key]] = $value[$ds->value];
			}
			return $data;
		} else return NULL;
		
		
	}
	
	/**
	* Carga o crea un nuevo Modelo Generico
	* @param undefined $modelo
	* 
	* @return
	*/
	protected function load_model( $modelo ) {
		
		$version = $GLOBALS['CREATIVE']['request']['version'];		
		$modelo =  $modelo . 'Model';
		$path_model = ROOT . $version .DS. 'models' .DS. $modelo . '.php';
		
		if (is_readable($path_model)) {			
			require_once $path_model;
			$modelo = new $modelo;
			return $modelo;		  
		} else {
			$path_model =  PATH_APP . 'ModelGenerator.php';			
			if (is_readable($path_model)) {
				$table = str_ireplace('Model','', $modelo);
				$ModelGenerator = 'ModelGenerator';
				$modelo = new $ModelGenerator($table);
		  		return $modelo;				
			} else {
				return FALSE;
			}
		}
	}
	
	
}





class DataSourceItem {
	
	private 
		$_datasource = array(),
		$_name = '';
	
	
	/**
	* Crea un nuevo Data Soruce
	* @param undefined $name Nombre del DataSource
	* @param undefined $property Propiedades de DAtaSource
	* 
	* @return
	*/
	public function create( $name, $property ){
		
		if( isset($property['source']) ){
			if( is_string($property['source']) ){
				$source = $this->load_model($property['source']);
			} else {
				$source = $property['source'];
			}
		} else {
			$source = $this->load_model($name);
		}	
		$this->_name = $name;
		$this->_datasource = (object) array(
			'source'=> $source,
			'key'	=> $property['key'],
			'value'	=> $property['value']
		);
		
		return $this;
	}
	
	
	/**
	* Devuelve el DataSource asociado al nombre
	* @param undefined $name
	* 
	* @return
	*/
	public function get_datasource(){
		return $this->_datasource;
	}
	
	
	/**
	* Devuelve el DataSource asociado al nombre
	* @param undefined $name
	* 
	* @return
	*/
	public function get_datails(){
		return array( 'name' => $this->_name, 'datasource'=>$this->_datasource);
	}
	
	
	/**
	* 
	* @param undefined $name
	* 
	* @return
	*/
	public function get_data( ){
		
		if( isset($this->_datasource) ){
			
			$ds = $this->_datasource;
			$data = array();
			
			foreach( $ds->source->all(array(), array($ds->value => 'ASC')) as $key => $value){
				$data[$value[$ds->key]] = $value[$ds->value];
			}
			return $data;
		} else return NULL;
		
		
	}
	
	/**
	* Carga o crea un nuevo Modelo Generico
	* @param undefined $modelo
	* 
	* @return
	*/
	protected function load_model( $modelo ) {
		
		//$version = $GLOBALS['CREATIVE']['request']['version'];		
		$modelo =  $modelo . 'Model';
		$path_model = PATH_APP . 'mvc' .DS. 'models' .DS. $modelo . '.php';
		
		if (is_readable($path_model)) {			
			require_once $path_model;
			$modelo = new $modelo;
			return $modelo;		  
		} else {
			$path_model =  PATH_APP . 'ModelGenerator.php';			
			if (is_readable($path_model)) {
				$table = str_ireplace('Model','', $modelo);
				$ModelGenerator = 'ModelGenerator';
				$modelo = new $ModelGenerator($table);
		  		return $modelo;				
			} else {
				return FALSE;
			}
		}
	}
	
	
}



?>