<?php

class Model {
	
    protected $conex;
	protected $table = '';
	protected $pk = '';
	protected $variables = NULL;
	
	protected $DB_USER ;
	protected $DB_PASSWORD ;
	protected $DB_HOST = 'localhost';
	protected $DB_DATABASE ;
	protected $DB_PORT ;	
	protected $DB_COLLATE ;
		
    public function __construct( $DB_USER=NULL, $DB_PASSWORD=NULL, $DB_DATABASE=NULL , $DB_HOST='localhost', $DB_PORT = '3306', $DB_COLLATE = 'utf8'){   
    
    	if( $DB_USER == NULL) {
			$this->DB_USER 		= DB_USER;
			$this->DB_PASSWORD 	= DB_PASSWORD;
			$this->DB_HOST 		= DB_HOST;
			$this->DB_DATABASE 	= DB_DATABASE;
			$this->DB_PORT 		= DB_PORT;
			$this->DB_COLLATE 	= DB_COLLATE;
		} else {
			$this->DB_USER 		= $DB_USER;
			$this->DB_PASSWORD 	= $DB_PASSWORD;
			$this->DB_HOST 		= $DB_HOST;
			$this->DB_DATABASE 	= $DB_DATABASE;
			$this->DB_PORT 		= $DB_PORT;
			$this->DB_COLLATE 	= $DB_COLLATE;
		}
		
			
		$this->conex = new Conexant($DB_USER, $DB_PASSWORD, $DB_DATABASE, $DB_HOST);		

    }

    protected function change_driver( $driver ){
		$this->conex->change_driver( $driver );
	}
	
    
    protected function validate_connection(){
		#Verificar Conexión con Base de Datos
		if( !$this->open_connection() ){			
			return array(
				'status' => 300,
				'response' => array(
					'message'=>'Se produjo un error de conexión'
				)
			);
		}
    }
    
    
	public function __set($name,$value){
		if(strtolower($name) === $this->pk) {
			$this->variables[$this->pk] = $value;
		}
		else {
			$this->variables[$name] = $value;
		}
	}
	
	
	public function __get($name){	
		if(is_array($this->variables)) {
			if(array_key_exists($name,$this->variables)) {
				return $this->variables[$name];
			}
		}
		return null;
	}
	
    /**
	* @param array $fields.
	* @param array $sort.
	* @return array of Collection.
	* Example: $user = new User;
	* $found_user_array = $user->search(array('sex' => 'Male', 'age' => '18'), array('dob' => 'DESC'));
	* // Will produce: SELECT * FROM {$this->table_name} WHERE sex = :sex AND age = :age ORDER BY dob DESC;
	* // And rest is binding those params with the Query. Which will return an array.
	* // Now we can use for each on $found_user_array.
	* Other functionalities ex: Support for LIKE, >, <, >=, <= ... Are not yet supported.
	*/
	public function search($fields = array(), $sort = array(), $lower=true) {
		$bindings = empty($fields) ? $this->variables : $fields;
		$sql = "SELECT * FROM " . $this->table;
		if (!empty($bindings)) {
			$fieldsvals = array();
			$columns = array_keys($bindings);
			foreach($columns as $column) {
				//$fieldsvals [] = ($lower ? "LOWER(" : "").$column . ($lower ? ")" : "") . " = :". $column;
				$fieldsvals [] = $column . " = :". $column;
				$this->variables[$column] = $bindings[$column];
			}
			$sql .= " WHERE " . implode(" AND ", $fieldsvals);
		}
		
		if (!empty($sort)) {
			$sortvals = array();
			foreach ($sort as $key => $value) {
				$sortvals[] = $key . " " . $value;
			}
			$sql .= " ORDER BY " . implode(", ", $sortvals);
		}
		return $this->exec($sql);
	}


    /**
	* @param array $fields.
	* @param array $sort.
	* @return array of Collection.
	* Example: $user = new User;
	* $found_user_array = $user->search(array('sex' => 'Male', 'age' => '18'), array('dob' => 'DESC'));
	* // Will produce: SELECT * FROM {$this->table_name} WHERE sex = :sex AND age = :age ORDER BY dob DESC;
	* // And rest is binding those params with the Query. Which will return an array.
	* // Now we can use for each on $found_user_array.
	* Other functionalities ex: Support for LIKE, >, <, >=, <= ... Are not yet supported.
	*/
	public function search_or($fields = array(), $sort = array(), $lower=true) {
		$bindings = empty($fields) ? $this->variables : $fields;
		$sql = "SELECT * FROM " . $this->table;
		if (!empty($bindings)) {
			$fieldsvals = array();
			$columns = array_keys($bindings);
			foreach($columns as $column) {
				$fieldsvals [] = ($lower ? "LOWER(" : "").$column . ($lower ? ")" : "") . " = :". $column;
				$this->variables[$column] = $bindings[$column];
			}
			$sql .= " WHERE " . implode(" OR ", $fieldsvals);
		}
		
		if (!empty($sort)) {
			$sortvals = array();
			foreach ($sort as $key => $value) {
				$sortvals[] = $key . " " . $value;
			}
			$sql .= " ORDER BY " . implode(", ", $sortvals);
		}
		return $this->exec($sql);
	}
	

    /**
	* @param array $fields.
	* @param array $sort.
	* @return array of Collection.
	* Example: $user = new User;
	* $found_user_array = $user->search(array('sex' => 'Male', 'age' => '18'), array('dob' => 'DESC'));
	* // Will produce: SELECT * FROM {$this->table_name} WHERE sex = :sex AND age = :age ORDER BY dob DESC;
	* // And rest is binding those params with the Query. Which will return an array.
	* // Now we can use for each on $found_user_array.
	* Other functionalities ex: Support for LIKE, >, <, >=, <= ... Are not yet supported.
	*/
	public function search_like_or($fields = array(), $sort = array()) {
		$bindings = empty($fields) ? $this->variables : $fields;
		$sql = "SELECT * FROM " . $this->table;
		if (!empty($bindings)) {
			$fieldsvals = array();
			$columns = array_keys($bindings);
			foreach($columns as $column) {
				$fieldsvals [] = $column . " LIKE '%".$bindings[$column]."%'";
			}
			$sql .= " WHERE " . implode(" OR ", $fieldsvals);
		}
		
		if (!empty($sort)) {
			$sortvals = array();
			foreach ($sort as $key => $value) {
				$sortvals[] = $key . " " . $value;
			}
			$sql .= " ORDER BY " . implode(", ", $sortvals);
		}
		return $this->exec($sql);
	}
	
	
	
    /**
	* @param array $fields.
	* @param array $sort.
	* @return array of Collection.
	* Example: $user = new User;
	* $found_user_array = $user->search(array('sex' => 'Male', 'age' => '18'), array('dob' => 'DESC'));
	* // Will produce: SELECT * FROM {$this->table_name} WHERE sex = :sex AND age = :age ORDER BY dob DESC;
	* // And rest is binding those params with the Query. Which will return an array.
	* // Now we can use for each on $found_user_array.
	* Other functionalities ex: Support for LIKE, >, <, >=, <= ... Are not yet supported.
	*/
	public function search_like($fields = array(), $sort = array()) {
		$bindings = empty($fields) ? $this->variables : $fields;
		$sql = "SELECT * FROM " . $this->table;
		if (!empty($bindings)) {
			$fieldsvals = array();
			$columns = array_keys($bindings);
			foreach($columns as $column) {
				$fieldsvals [] = $column . " LIKE '%:". $column."%'";
			}
			$sql .= " WHERE " . implode(" AND ", $fieldsvals);
		}
		
		if (!empty($sort)) {
			$sortvals = array();
			foreach ($sort as $key => $value) {
				$sortvals[] = $key . " " . $value;
			}
			$sql .= " ORDER BY " . implode(", ", $sortvals);
		}
		return $this->exec($sql);
	}
	
	
	
	public function filter_withuserid( $id, $fields, $filter, $value ){
		
		if( !in_array( $filter, $fields) or $value == ''){
			array(
				"status"=>300,
				"response"=>array(
					"message"=>'Los parametros de busqueda ingresados no son válidos, o están vacíos',
					"icon"=>'warning'
				)
			);	
		}
		
		if( $filter == 'all' ){
			$columns = '';
			$values = array($id);
			foreach( $fields as $key => $val){
				if( $val !== 'all' ){
					$columns .= $val . " LIKE ? OR ";
					array_push($values, "%".$value."%");
				}
			}
			
			$columns = substr($columns,0, strlen($columns)-4);
			$data = $this->exec("
				SELECT * FROM ".$this->table." WHERE user_id = ? AND (".$columns.")
			", $values);
		} else {
			$data = $this->exec("
				SELECT * FROM ".$this->table." WHERE user_id = ? AND ".$filter." LIKE ?
			", array($id, "%".$value."%"));
		}
		
		return array("status"=>200,"response" =>array('data'=>$data) );
      	
	}
	
	
	
	
	public function filter( $fields, $filter, $value, $addon = '' ){
		
		if( !in_array( $filter, $fields) or $value == ''){
			array(
				"status"=>300,
				"response"=>array(
					"message"=>'Los parametros de busqueda ingresados no son válidos, o están vacíos',
					"icon"=>'warning'
				)
			);	
		}
		
		if( $filter == 'all' ){
			$columns = '';
			$values = array($id);
			foreach( $fields as $key => $val){
				if( $val !== 'all' ){
					$columns .= $val . " LIKE ? OR ";
					array_push($values, "%".$value."%");
				}
			}
			
			$columns = substr($columns,0, strlen($columns)-4);
			$data = $this->exec("
				SELECT * FROM ".$this->table." WHERE {$addon} (".$columns.")
			", $values);
		} else {
			$data = $this->exec("
				SELECT * FROM ".$this->table." WHERE {$addon} ".$filter." LIKE ?
			", array($id, "%".$value."%"));
		}	
		
		return array("status"=>200,"response" =>$data);
      	
	}
	
	
	public function exec($sql, $array = null) {
		
		if($array !== null) {
			$result =  $this->conex->execute($sql, $array);	
		}
		else {
			$result =  $this->conex->execute($sql, $this->variables);	
		}
		
		// Empty bindings
		$this->variables = array();
		return $result;
	}
	
	    /**
     *  Devuelve el ultimo ID autonumerico insertado
     *  @return string
     */
    public function last_id(){
		return $this->conex->last_insert_id();
    }
    
    
	/**
	* Obtiene todos los registro de una tabla
	* @param undefined $sort
	* 
	* @return
	*/
    public function all( $field_others = array(), $sort = array() ){
    	$sql_sort = '';
    	if (!empty($sort)) {
			$sortvals = array();
			foreach ($sort as $key => $value) {
				$sortvals[] = $key . " " . $value;
			}
			$sql_sort .= " ORDER BY " . implode(", ", $sortvals);
		}
		
		$fields = '';
		if( count($field_others)>0 ){
			foreach($field_others as $key => $value){
				$fields .= ', ' . $value .' AS '. $key;
			}
		}
		$fields = ' '.$fields.' ';
		
		return $this->exec("SELECT *" .$fields. " FROM {$this->table} " . $sql_sort ) ;
	}



	/**
	* Agreag un nuevo resgistro a una tabla
	* 
	* @return
	*/
	public function create() { 
		$bindings   	= $this->variables;
		if(!empty($bindings)) {
			$fields     =  array_keys($bindings);
			$fieldsvals =  array(implode(",",$fields),":" . implode(",:",$fields));
			$sql 		= "INSERT INTO ".$this->table." (".$fieldsvals[0].") VALUES (".$fieldsvals[1].")";
		}
		else {
			$sql 		= "INSERT INTO ".$this->table." () VALUES ()";
		}
		return $this->exec($sql);
	}
	
	
	/**
	* Actualiza los datos de un registro
	* @param undefined $id
	* 
	* @return
	*/
	public function update( $fields = 0/*array()*/ ) {
		
		/*if (!empty($fields)) {
			$fields_filters = array();
			$fieldsvals = '';
			$filter = '';
			
			
			//Condición
			$columns = array_keys($fields);
			foreach($columns as $column) {
				$fields_filters [] = $column ." = :". $column;
				if( isset($this->variables[$column]) ){
					$this->variables[$column] = $fields[$column];
				}
				
			}
			$filter = implode(" AND ", $fields_filters);
			
			
			//Valores
			$columns = array_keys($this->variables);
			foreach($columns as $column){
				if($column !== $this->pk){
					$fieldsvals .= $column . " = :". $column . ",";
				}
			}
			$fieldsvals = substr_replace($fieldsvals , '', -1);
			

			
			
			if(count($columns) > 1 ) {
				$sql = "UPDATE " . $this->table ." SET ". $fieldsvals ." WHERE ". $filter;
				return $this->exec($sql);
			}
		
		
		} else {*/
			$this->variables[$this->pk] = (empty($this->variables[$this->pk])) ? $fields : $this->variables[$this->pk];
			$fieldsvals = '';
			$columns = array_keys($this->variables);
			foreach($columns as $column){
				if($column !== $this->pk)
				$fieldsvals .= $column . " = :". $column . ",";
			}
			$fieldsvals = substr_replace($fieldsvals , '', -1);
			
			if(count($columns) > 1 ) {
				$sql = "UPDATE " . $this->table .  " SET " . $fieldsvals . " WHERE " . $this->pk . "= :" . $this->pk;
				if($fields === "0" && $this->variables[$this->pk] === "0") { 
					unset($this->variables[$this->pk]);
					$sql = "UPDATE " . $this->table .  " SET " . $fieldsvals;
				}
				return $this->exec($sql);
			}
		
		/*}*/
		
		
	
		return null;
	}
	
	/**
	* Devuelve el número de filas afectadas por una sentencia DELETE, INSERT, o UPDATE.
	* 
	* @return
	*/
	public function affected(){
		$result = $this->conex->row_count();
		return $result;
	}
	
	/**
	* Elimina un registro específico por su clave primaria
	* @param undefined $id 
	* 
	* @return
	*/
	public function delete($id = "") {
		$id = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];
		if(!empty($id)) {
			$sql = "DELETE FROM " . $this->table . " WHERE " . $this->pk . "= :" . $this->pk. " LIMIT 1" ;
		}
		return $this->exec($sql, array($this->pk=>$id));
	}
	
	
	/**
	* Busca un registro por su clave primaria
	* @param undefined $id
	* 
	* @return
	*/
	public function find($field_others = "") {
		
		$id = $this->variables[$this->pk];
		
		$fields = '';
		if( count($field_others)>0 ){
			foreach($field_others as $key => $value){
				$fields .= ', ' . $value .' AS '. $key;
			}
		}
		$fields = ' '.$fields.' ';
		
		if(!empty($id)) {
			$sql = "SELECT *" .$fields. " FROM " . $this->table ." WHERE " . $this->pk . "= :" . $this->pk ;	
			
			$result = $this->conex->row($sql, array($this->pk=>$id));
			return ($result != false) ? $result : null;
		}
	}
	
	/**
	* Minimo
	* @param undefined $field
	* 
	* @return
	*/
	public function min($field)  {
		if($field)
		return $this->single("SELECT min(" . $field . ") `min` FROM " . $this->table);
	}
	
	/**
	* Maximo
	* @param undefined $field
	* 
	* @return
	*/
	public function max($field)  {
		if($field)
		return $this->single("SELECT max(" . $field . ") `max` FROM " . $this->table);
	}
	
	/**
	* Promedio
	* @param undefined $field
	* 
	* @return
	*/
	public function avg($field)  {
		if($field)
		return $this->single("SELECT avg(" . $field . ") `avg` FROM " . $this->table);
	}
	
	/**
	* Sumatoria
	* @param undefined $field
	* 
	* @return
	*/
	public function sum($field)  {
		if($field)
		return $this->single("SELECT sum(" . $field . ") `sum` FROM " . $this->table);
	}
	
	/**
	* Cantidad
	* @param undefined $field
	* 
	* @return
	*/
	public function count($field)  {
		if($field)
		return $this->single("SELECT count(" . $field . ") `count` FROM " . $this->table);
	}	
	
	/*public function filter($data){
		return htmlentities( addslashes($data), ENT_QUOTES, 'UTF-8', false );
	}*/
    
    protected function get(){

    }
    protected function set(){

    }
    protected function edit(){

    }

}

