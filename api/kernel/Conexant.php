<?php

include_once ROOT .DS. 'configuration.php';

/**
* Esta clase permite crear conexiones con MySQL y hacer consultas a base de datos
*/
class Conexant {
		
	protected $DB_USER 		= NULL,
			$DB_PASSWORD 	= NULL,
			$DB_HOST 		= NULL,
			$DB_DATABASE 	= NULL,
			$DB_PORT 		= NULL,
			$DB_COLLATE 	= NULL,
			$connection		= NULL;
	
	
    private $error 		= NULL,
    		$result 	= NULL,    		
    		$conected 	= FALSE,
			$options 	= array(
				PDO::ATTR_PERSISTENT			=> true,
				PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES		=> false,
				PDO::MYSQL_ATTR_INIT_COMMAND 	=> "SET NAMES utf8"
		    );
    
    /**
	* 
	* 
	* @return
	*/
    public function __construct($DB_USER = NULL, $DB_PASSWORD = NULL, $DB_DATABASE = NULL, $DB_HOST = 'localhost', $DB_PORT = '3306', $DB_COLLATE = 'utf8' ) {
		
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
		
    	mb_internal_encoding( 'UTF-8' );
		mb_regex_encoding( 'UTF-8' );
    }
    
    /**
	* Esta es la función que se conecta a la base de datos, 
	* en caso de existir un error en la conexión, 
	* lo almacena en el log de errores de PHP
	* 
	* @param string $DB_USER Usuario
	* @param string $DB_PASSWORD Contraseña de conexión
	* @param string $DB_HOST Host de conexión
	* @param string $DB_DATABASE Base de datos
	* @param string $DB_PORT Puerto de escucha apra conexión. Por defecto 3306.
	* @param string $DB_COLLATE Por defecto 'utf8'
	* 
	* @return
	*/
	public function open() {
		
		try {
			$dsn = 'mysql:dbname='.$this->DB_DATABASE.';host='.$this->DB_HOST.';port='.$this->DB_PORT.';charset='.$this->DB_COLLATE;
			$this->connection = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD, $this->options);
			$this->conected = TRUE;
			return $this->conected;
		} catch (PDOException $ex) {
						
			error_log($ex->getMessage());
			
			throw new ExceptionAPI(500,'Could not connect to database');	
			
			return FALSE;
		}
	}
    
    
   
    
    /**
    * Desconecta y cierra la conexión activa con la base de datos
    * @return void
    */
    public function close() {
    	try{
    		if( $this->conected ){
				$this->connection = null;
		        $this->connection = NULL;
		        $this->conected = FALSE;
			}
		} catch ( Excepcion $ex) {
			
		}
        
    }
    
    /**
	* Esta función nos devuelve el número de error (en caso de haberlo) 
	* al haberse ejecutado una consulta o procedimiento.
	* 
	* @return
	*/
    public function get_errno() {
        return $this->resource->errno;
    }
    
    
    
  	/**
	* Devuelve el número de filas modificadas o borradas por la 
	* sentencia SQL ejecutada. Si no hay filas afectadas devuelve 0.
	* @param string $query Consulta SQL
	* 
	* @return
	*/
	public function query( $query ) {
  	
		if (!$this->connection) {
			$this->open( $this->DB_USER, $this->DB_PASSWORD, $this->DB_HOST, $this->DB_DATABASE );
		}

		try {
			$std = $this->connection->query($query);
			return $std;
		} catch (Exception $ex) {
			echo '<strong>Error [query]: </strong> '.$ex->getMessage().'<br/>';
			echo '<strong>Query: </strong> '.$query.'<br/>';
			return FALSE;
		}
	}
    
    
    /**
	* 
	* @param undefined $query
	* 
	* @return
	*/
    private function preformat_query( $query ){
		return 
			trim(
				str_replace(array(chr(13).chr(10), "\r\n", "\n", "\r", "\t"), array("", "", "", "", " "), $query)
			);
	}
    
    
	/**
	* Ejecuta una consulta preparada, 
	* limpia los valores ingresados de palabras reservadas MySQL,
	* y escapa tods los caracteres
	* 
	* @param string $query Consulta SQL
	* @param array $params Parametros de la consulta
	* @param int $fetchmode Tipo de resultado obtenido [object|array]
	* 
	* @return mixed
	*/
	public function execute( $query, $params = array(), $fetchmode = PDO::FETCH_ASSOC ) {
		$result = array();
		
	  	if ( !$this->conected ) {
	        $this->open( $this->DB_USER, $this->DB_PASSWORD, $this->DB_HOST, $this->DB_DATABASE );
	    }
	        
    	$query = $this->preformat_query($query);
		
    	try {
    		
			if ( ($this->stmt = $this->connection->prepare($query)) ) {
				
				if ( !$this->stmt->execute( $params ) ) {
					
				}
				
				if( stripos($query, 'select') !==FALSE OR stripos($query, 'show') !==FALSE ){
					$result = $this->stmt->fetchAll( $fetchmode );
				}
				$this->stmt->closeCursor();
				return $result;
			}

		} catch(PDOException $ex){
			/*if ($this->connection->getErrorNo()) {
	            error_log($this->connection->getError());
	        }*/
	        throw new ExceptionAPI(500,$ex->getMessage());	
		}
			
		return $result;
    }
    


	/**
	* Agrega pameros a la consulta
	* 
	* @param undefined $param Placeholder del valor que se usará en la consulta
	* @param undefined $value Valor del parametro
	* @param undefined $type Tipo de parametro. Por defecto NULL
	* 
	* @return
	*/
	public function bind($param, $value, $type = null){
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
				  $type = PDO::PARAM_INT;
				  break;
				case is_bool($value):
				  $type = PDO::PARAM_BOOL;
				  break;
				case is_null($value):
				  $type = PDO::PARAM_NULL;
				  break;
				default:
				  $type = PDO::PARAM_STR;
			}
		}
		
		$this->stmt->bindValue($param, $value, $type);
	}


   
    /**
	* Devuelve el número de filas afectadas por una sentencia DELETE, INSERT, o UPDATE.
	* 
	* @return
	*/
    public function row_count(){
		$result = $this->stmt->rowCount();
		return $result;
	}
    
   
       
    /**
	* Ejecuta una consulta de tipo escalar
	* 
	* @param string $query
	* @param array $params
	* 
	* @return
	*/
	public function row($query, $params = NULL, $fetchmode = PDO::FETCH_ASSOC ) {
        $result = $this->execute($query, $params);
        if (!is_null($result)) {
            if (!is_object($result)) {
				return count($result)>0 ? $result[0] : array();
            } else {
               // $row = $this->connection->fetchArray($result);
               //return $row[0];
               return $result[0];
            }
        }
        return null;
    }
    
    
	private function prepare($sql, $params) {
        $escaped = '';
        if ($params) {
            foreach ($params as $key => $value) {
                if (is_bool($value)) {
                    $value = $value ? 1 : 0;
                } elseif (is_double($value)) {
                    $value = str_replace(',', '.', $value);
                } elseif (is_numeric($value)) {
                    if (is_string($value)) {
                        $value = "'" . $this->provider->escape($value) . "'";
                    } else {
                        $value = $this->provider->escape($value);
                    }
                } elseif (is_null($value)) {
                    $value = "NULL";
                } else {
                    $value = "'" . $this->provider->escape($value) . "'";
                }
                $escaped[] = $value;
            }
        }
        $this->params = $escaped;
        $q = preg_replace_callback("/(\?)/i", array($this, "replaceParams"), $sql);
        return $q;
    }

    /**
     * Iniciar una transacción
     * @return boolean, true on success or false on failure
     */
    public function begin(){
    	if ( !$this->conected ) {
	        $this->open( $this->DB_USER, $this->DB_PASSWORD, $this->DB_HOST, $this->DB_DATABASE );
	    }
        return $this->connection->beginTransaction();
    }
    
    /**
     * confirmar una transacción
     *  @return boolean, true on success or false on failure
     */
    public function commit(){
        return $this->connection->commit();
    }
    
    /**
     *  Revertir una transacción
     *  @return boolean, true on success or false on failure
     */
    public function rollback(){
        return $this->connection->rollBack();
    }
    
    
  /**
     * Ejecuta una consulta
     * @param String La consulta
     * @return void
     */
    public function execute_call($sp, $parametros = null) {
    	 if( empty( $parametros ) ){
            return false;
        }
    		$sql = "CALL `{$sp}` ";
        $values = array();
        foreach( $parametros as $field => $value ){
            $values[] = "'" .$value. "'";
        }
        
        $sql .= ' ('. implode(', ', $values) .')';
        $results = $this->connection->query( $sql );
        if( $this->connection->error ) {
            $this->log_db_errors( $this->connection->error, $sql );
            return false;
        }
        else{
            $row = array();
            while( $r = $results->fetch_assoc() ) {
                $row[] = $r;
            }
            $row = $row[0];
            return $row;   
        }
    }



    /**
     *  Devuelve el ultimo ID autonumerico insertado
     *  @return string
     */
    public function last_insert_id(){
		return $this->connection->lastInsertId();
    }
    

 	
	
 /**
   * Sanitize user data
   *
   * Example usage:
   * $user_name = $database->filter( $_POST['user_name'] );
   * 
   * Or to filter an entire array:
   * $data = array( 'name' => $_POST['name'], 'email' => 'email@address.com' );
   * $data = $database->filter( $data );
   *
   * @access public
   * @param mixed $data
   * @return mixed $data
   */
	public function filter( $data ){
		if( !is_array( $data ) ){
			$data = mysqli_real_escape_string( $this->connection, $data );
			$data = trim( htmlentities( $data, ENT_QUOTES, 'UTF-8', false ) );
     }
     else{
         //Self call function to sanitize array data
         $data = array_map( array( $this, 'filter' ), $data );
     }
     return $data;
	}
    

	
	/**
    * Destruir el objeto
    * Cierra todas las conexiones a la base de datos
    */
    public function __deconstruct() {
        foreach ( $this->connections as $connections ) {
            $connections->close();
        }
    }

}
