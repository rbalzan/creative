<?php


define('DB_DRIVER_MYSQL', 'mysql');
define('DB_DRIVER_MSSQL', 'mssql');
define('DB_DRIVER_PGSQL', 'pgsql');


/**
* Esta clase permite crear conexiones con MySQL y hacer consultas a base de datos
*/
class Conexant {
	
	const 
		DNS_MYSQL = 'mysql:dbname=@dbname;host=@host;port=@port;charset=@charset',
		DNS_MSSQL = 'sqlsrv:Server=@host;Database=@dbname;ConnectionPooling=0',
		DNS_PGSQL = 'pgsql:dbname=@dbname;host=@host';
	
	protected 
		$DRIVER			= DB_DRIVER_DATABASE,
		$DB_USER 		= NULL,
		$DB_PASSWORD 	= NULL,
		$DB_HOST 		= NULL,
		$DB_DATABASE 	= NULL,
		$DB_PORT 		= NULL,
		$DB_COLLATE 	= NULL,
		$connection		= NULL;
	
    private 
    	$error 		= NULL,
		$result 	= NULL,
		$conected 	= FALSE,
		$options 	= array(
			PDO::ATTR_PERSISTENT			=> FALSE,
			PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES		=> FALSE,
			//PDO::MYSQL_ATTR_INIT_COMMAND 	=> "SET NAMES utf8"
	    );
    
    /**
	* 
	* 
	* @return
	*/
    public function __construct($DB_USER = NULL, $DB_PASSWORD = NULL, $DB_DATABASE = NULL, $DB_HOST = 'localhost', $DB_PORT = '3306', $DB_COLLATE = 'utf8' ) {
		if( $DB_USER != NULL )
			$this->open($DB_USER, $DB_PASSWORD, $DB_DATABASE, $DB_HOST, $DB_PORT, $DB_COLLATE);
    }
    
    public function change_driver( $driver ){
		$this->DRIVER = $driver;
	}
    
    /**
	* Esta es la función que se conecta a la base de datos, 
	* en caso de existir un error en la conexión, 
	* lo almacena en el log de errores de PHP
	* @return
	*/
	public function open( $DB_USER = NULL, $DB_PASSWORD = NULL, $DB_DATABASE = NULL, $DB_HOST = 'localhost', $DB_PORT = '3306', $DB_COLLATE = 'utf8' ) {		
		
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
		
		try {			
			//Seleccionar el Driver de Base de Datos por defecto para armar el DSN
			switch( $this->DRIVER ){			
				case DB_DRIVER_MSSQL:
					$dsn = str_ireplace(
						array('@dbname','@host'),
						array($this->DB_DATABASE, $this->DB_HOST),
						self::DNS_MSSQL
					);
					$this->connection = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD);
				break;
				
				case DB_DRIVER_PGSQL:
					$dsn = str_ireplace(
						array('@dbname','@host'),
						array($this->DB_DATABASE, $this->DB_HOST),
						self::DNS_PGSQL
					);
					$this->connection = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD);
				break;
				
				default :
					$dsn = str_ireplace(
						array('@dbname', '@host','@port', '@charset'),
						array($this->DB_DATABASE, $this->DB_HOST, $this->DB_PORT, $this->DB_COLLATE),
						self::DNS_MYSQL
					);
					$this->connection = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD, $this->options);
				break;
			}
			
			$this->conected = TRUE;
			return $this->conected;
		} catch (PDOException $ex) {
						
			error_log($ex->getMessage());
						
			if( ENVIRONMENT == 'development' ){
				
				$title = 'Could not connect to database';
				
				$out  = '<strong style="color:red">'.$ex->getMessage().'</strong><br/>';
				$out .= '<strong>File:</strong> '.$ex->getFile().'<br/>';
				$out .= '<strong>Method:</strong> '. __FUNCTION__ .'<br/>';
				$out .= '<strong>Line:</strong> '.$ex->getLine();
				
				$add .= '<strong>Connection Data:</strong><br/>';
				$add .= '<strong>Server:</strong> '.$this->DB_HOST.'<br/>';
				$add .= '<strong>DataBase:</strong> '.$this->DB_DATABASE.'<br/>';
				$add .= '<strong>User:</strong> '.$this->DB_USER.'<br/>';
				$add .= '<strong>Password:</strong> '.$this->DB_PASSWORD.'<br/>';
				$add .= '<strong>Port:</strong> '.$this->DB_PORT.'<br/>';
				$add .= '<strong>Collate:</strong> '.$this->DB_COLLATE.'<br/><br/>';
				
				$out = '<strong>Directory Module: </strong>'. $dirModulo ;
				CreativeBase::run_exception('Module not found', $out, $add);
					
				
			} else {
				$message = 'Erro de conexión con la base de datos.';
			}
			
			die( $html );
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
    public function get_error_no() {
        return $this->resource->errno;
    }
    
    /**
	* Esta función nos devuelve el mensaje de error (sin el número).
	* 
	* @return
	*/
    public function get_error() {
        return $this->resource->error;
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
			
			$out = $ex->getMessage().'<br/>';
			CreativeBase::run_exception('Error in Query not found', $out);
					
					
			//echo '<strong>Error [query]: </strong> '.$ex->getMessage().'<br/>';
			//echo '<strong>Query: </strong> '.$query.'<br/>';
			return FALSE;
		}
	}

	protected $stmt;
	public function single($query, $params = null){
        if (!$this->connection) {
	        $this->open( $this->DB_USER, $this->DB_PASSWORD, $this->DB_HOST, $this->DB_DATABASE );
	    }
	    
	    $query = trim(str_replace(array(chr(13).chr(10), "\r\n", "\n", "\r"), array("", "", "", ""), $query));
    	$query = strtolower($query);
    	
    	if ( ($this->stmt = $this->connection->prepare($query)) ) {
			try {
				if ( !$this->stmt->execute( $params ) ) {
					$result = $this->stmt->errorInfo();
					print_r($this->stmt->errorInfo());
					return $result;
				}
				$result = $this->stmt->fetchColumn();
				$this->stmt->closeCursor();
				return $result;

			} catch(PDOException $e){
				/*if ($this->connection->getErrorNo()) {
		            error_log($this->connection->getError());
		        }*/
				echo $e->getMessage();
			}
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
				str_replace(
					array(
						chr(13).chr(10),
						"\r\n",
						"\n",
						"\r",
						"\t"
					),
					array("", "", "", "", " "), $query)
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
		$result = NULL;
		
	  	if ( !$this->conected ) {
	        $this->open( $this->DB_USER, $this->DB_PASSWORD, $this->DB_DATABASE, $this->DB_HOST );
	    }
	    
	    $_query = $query;
    	$query = $this->preformat_query($query);
		
    	try {
    		
			if ( ($this->stmt = $this->connection->prepare($query)) ) {
				
				if ( !$this->stmt->execute( $params ) ) {
					$result = $this->stmt->errorInfo();
					print_r($this->stmt->errorInfo());
					return $result;
				}
				if( stripos($query, 'select') !==FALSE OR stripos($query, 'show') !==FALSE){
					$result = $this->stmt->fetchAll( $fetchmode );
				}
				
				$this->stmt->closeCursor();
			}

		} catch(PDOException $ex){
			
			$out  = '<strong style="color:red">'.$ex->getMessage().'</strong><br/>';
			$out .= '<strong>File:</strong> '.$ex->getFile().'<br/>';
			$out .= '<strong>Method:</strong> '. __FUNCTION__ .'<br/>';
			$out .= '<strong>Line:</strong> '.$ex->getLine();
			
			$add =  '<strong>Query: </strong><br/><pre>' . $_query.'</pre>';
			if( count($params)>0 ){
				$add .= '<strong>Parameters: </strong>';
				//$add .= '<br/>Paramns: ' . var_dump ($params);
			}			
			ErrorHandler::run_exception('Error executing Query', $out, $add);
		}
			
		return $result;
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
	* 
	* 
	* @return
	*/
	public function table_exists($table){
		/*$table_in_db = @mysql_query('SHOW TABLES FROM '.$this->DB_DATABASE.' LIKE "'.$table.'"');
        if($table_in_db){
        	if(mysql_num_rows($table_in_db)==1){
                return true; // The table exists
            }else{
            	array_push($this->result,$table." does not exist in this database");
                return false; // The table does not exist
            }
        }*/
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
    
    
    /**
	* Cambia la base de datos
	* @param undefined $database
	* 
	* @return
	*/
    public function change_database($database) {
        $this->connection->changeDB($database);
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
	* Extra function to filter when only mysqli_real_escape_string is needed
	* @access public
	* @param mixed $data
	* @return mixed $data
	*/
	public function escape( $data ){
	   if( !is_array( $data ) ){
	       $data = $this->link->real_escape_string( $data );
	   }
	   else {
	       //Self call function to sanitize array data
	       $data = array_map( array( $this, 'escape' ), $data );
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
