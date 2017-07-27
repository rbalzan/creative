<?php

/**
 * 
 * 
 * @copyright   © 2017 Brayan Rincon
 * @version     1.0.0
 * @author      Brayan Rincon <brayan262@gmail.com>
*/
class Acl {
	
	private $_registry;
	private $_db;
	private $_id;
	private $_profile;
	private $_permissions;
	private $_ambit;
	private $_table = 'users';
	private $_conex ;
	private $_permissions_profile;
	private $_permissions_user;
	
	
	/**
	* 
	* @param undefined $ambit Ambito del Usuario ['backend','frontend']
	* @param undefined $id
	* 
	* @return
	*/
    public function __construct( $ambit = NULL) {
    	
		if( ! Creative::get( 'Session' )->get('auth') ){
			return;
		}

    	$this->_ambit = $ambit;    	
		
		
        /*if($id){
            $this->_id = $id;
        } else{
        	#Si ha iniciado sesión
            if( isset(Session::get($ambit)['auth']) ){
                $this->_id = Session::get($ambit)['id'];
            }else{ 
            	#Sino ha iniciado sesión, no se establecerán permisos
                $this->_id = 0;
            }
        }*/
        
		
        $this->_profile = $this->get_profile();
        $this->_permissions_profile = $this->get_permissions_profile();
        $this->_permissions_user = $this->get_permissions_user( $this->_id );
        $this->compile();
    }
    
    
	/**************************************************
	* Obtiene el ID del Perfil del Usuario
	* 
	* @return ID del Perfil
	*/
    public function get_profile(){
    	if( $this->_id ){
			$conex = new Conexant;
	    	$profile = $conex->execute(
	            "SELECT 
	            	profile_id 
	            FROM {$this->_table} 
	            WHERE id = {$this->_id}"
	        );
	        
	        if( is_array($profile) and count($profile) ){
				return $profile[0]['profile_id'];
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
    	
        
    }
    
    
    /**************************************************
	* Obtener los permisos del Perfil
	* 
	* @return
	*/
	public function get_permissions_profile(){
		
		$roles = $this->_conex->execute("
			SELECT 
				* 
			FROM profiles_permissions 
			WHERE 
				profile_id = '{$this->_profile}'
		");
		
		$data = array();
		$data['rolname'] = '';
		$data['modulo'] = array();
		$data['view'] = array();
		 
		foreach( $roles as $key => $value ){
			if( $value['attr'] == 'rolname' ){
				$data['rolname'] = $value['_value'];
			}
			
			if( $value['attr'] == 'permiso-modulo' ){
				$data['modulo'][$value['attrkey']] = $this->setear($value['attrvalue'], TRUE);
			}
			
			if( $value['attr'] == 'permiso-view' ){
				$data['view'][$value['attrkey']] = $this->setear($value['attrvalue'], TRUE);
			}
		}
		
		return $data;
		
    }
    
    
    /**************************************************
	* Obtener los permisos del Usuario
	* 
	* @return
	*/
    public function get_permissions_user( $user_id ){
    	
		$permissions = $this->_conex->execute("
			SELECT 
				meta.*, 
				prof.id rol_id 
			FROM users_meta meta 
				LEFT JOIN profiles prof ON meta.content = prof.name 
			WHERE 
				meta.user_id = '{$user_id}' AND 
				(meta.attr = 'module' OR  meta.attr = 'action' OR meta.attr = 'rolname')
		");
		
		$data = array();
		$data['rolname'] = '';
		$data['modulo'] = array();
		$data['view'] = array();
		 
		foreach( $permissions as $key => $value ){
			if( $value['attr'] == 'rolname' ){
				$data['rolname'] = $value['_value'];
			}
			
			if( $value['attr'] == 'permiso-modulo' ){
				$data['modulo'][$value['_key']] = $this->setear($value['_value'], FALSE);
			}
			
			if( $value['attr'] == 'permiso-view' ){
				$data['view'][$value['_key']] = $this->setear($value['_value'], FALSE);
			}
		}
		
		return $data;
		
    }
    
    
    
    private function setear( $mod, $heredado = FALSE ){
    	
    	$modules = Session::get('backend')['profile']['modules'];
    	
    	if( $mod != 'index' AND !isset($modules[$mod]) ){
			
		} else {
			
			$seteado = array();
			$module = $modules[$mod];
			$module = explode(',',$module);
			
			foreach( $module as$key => $value ){
 				$permissions = explode(':', $value);
				//foreach( $permissions as $k => $v ){
					$_mod = explode(':',$v);
					$seteado[$permissions[0]] = array( 'value'=>$permissions[1], 'heredado'=>$heredado);
				//}
			}
			return $seteado;
		}
    	
		
	}
	
	/**
	* Compilar los Accesos al Control de Listas
	* 
	* @return
	*/
    public function compile(){
    	if( $this->_id ){
			$this->_permissions = array_merge(
				$this->_permissions_profile,
				$this->_permissions_user
			);
		}
	}
    
    
    /**
	* Obtener todos los permisos del usuario
	* 
	* @return
	*/
    public function get_permissions(){
        if(isset($this->_permissions) && count($this->_permissions))
            return $this->_permissions;
    }
    

    public function permiso_modulo($key){
        if(array_key_exists($key, $this->_permissions)){
            if($this->_permissions[$key]['valor'] == true || $this->_permissions[$key]['valor'] == 1){
                return true;
            }
        }        
        return false;
    }
    
    /**
	 * Retorna un valor que indica si el usuario está autenticado
	 * @param {String} $name nombre de la variable
	 *
	 * @return {object}
	 */
	static function auth( $ambito ){
		if(isset($_SESSION[$ambito]))
			return $_SESSION[$ambito]['auth'];
	}
	
	
	public function access_module( $ambit = BACKEND , $module, $level = 'r' ){ 
		
		$module = str_ireplace('Controller','',$module);
		
		//Verificar si el usuario está autenticado
		if( !$this->auth($ambit) ){
			header('location:' . BASE_URL . $ambit . '/signin/');
			exit;
		}
		
		if( $module == 'index' ){
			return TRUE;
		}
		
		//Determinar el tiempo de sessión y si ha expirado el tiempo
		Session::time_now($ambit);
		
		switch( $ambit ){
			
			case 'frontend':
				
			break;
			
			case 'backend':
				//Obtener el módulo de donde proveien la solicitud
				$module = str_ireplace('Controller','',$module);
				
				//Obtiener el usuario
				$user_id = Session::get($ambit)['id'];
				$this->reload_profile( $ambit, $user_id );			
				$permissions = $this->setear( $module );
				
				$per = $permissions[$level];
				if( $per['value']!='1' OR $permissions == NULL ){
					header('location: /errors/backend/400');
					exit;
				}
			break;
		}
    }
    
    
    /**
	* Indica si se tiene acceso a una vista, desde un vinculo que apunte a ella
	* @param undefined $ambit
	* @param undefined $module
	* @param undefined $level
	* 
	* @return
	*/
    public function access_view( $module, $ambit = BACKEND ){ 
		
		$module = str_ireplace('Controller','',$module);
		
		//Verificar si el usuario está autenticado
		if( !$this->auth($ambit) ){
			return FALSE;
		}
		
		if( $module == 'index' ){
			return TRUE;
		}
				
		switch( $ambit ){
			
			case 'frontend':
				
			break;
			
			case 'backend':
			
				//Obtiener el usuario
				$user_id = Session::get($ambit)['id'];
				$this->reload_profile( $ambit, $user_id );			
				$permissions = $this->setear( $module );
				
				$per = $permissions['r'];
				if( $per['value']!='1' OR $permissions == NULL ){
					return FALSE;
				} else return TRUE;;
			break;
		}
    }
    
	/**
	* 
	* @param undefined $usuario_id
	* 
	* @return
	*/
	public function reload_profile( $ambit = BACKEND, $user_id ){
		
		$roles = $this->_conex->execute("
			SELECT 
				m.user_id, 
				m.name, 
				m.content content, 
				(SELECT p.id FROM profiles p WHERE p.name = m.content) attr
			FROM users_meta m 
			WHERE 
				m.attr = 'profile_name'  AND  user_id = ? 
			UNION ALL 
			SELECT 
				m.user_id, 
				m.name, 
				m.content content, 
				m.attr
			FROM users_meta m 
			WHERE 
				m.attr = 'module'  AND  user_id = ? 
			UNION ALL 
			SELECT 
				m.user_id, 
				m.name, 
				m.content content, 
				m.attr
			FROM users_meta m 
			WHERE 
				m.attr = 'action' AND  user_id = ?
		", array( $user_id,$user_id,$user_id ));
		
		$permissions = array(
			'modules'	=> array(),
			'actions'	=> array(),
		);
		
		 
		foreach( $roles as $key => $value ){
			if( $value['attr'] == 'profile_name' ){
				$permissions['profile_id'] 		= $value['attr'];
				$permissions['profile_name'] 	= $value['content'];
			}
			
			if( $value['attr'] == 'module' ){
				$permissions['modules'][$value['name']] = $value['content'];
			}
			
			if( $value['attr'] == 'action' ){
				$permissions['actions'][$value['name']] = $value['content'];
			}
		}
		
	
    	//Sesión de usuario del sistema
		$_SESSION[$ambit]['profile'] = $permissions;
		
	}
	
	
	
	
    /*
    public function get_modulo_nombre( $permisoID ){
        $permisoID = (int) $permisoID;
        
        $key = $this->_db->query(
                "select `permiso` from permisos " .
                "where id_permiso = {$permisoID}"
                );
                
        $key = $key->fetch();
        return $key['permiso'];
    }
        
    public function _get_permisos(){
        if(isset($this->_permisos) && count($this->_permisos))
            return $this->_permisos;
    }
    
    public function permiso($key){
        if(array_key_exists($key, $this->_permisos)){
            if($this->_permisos[$key]['valor'] == true || $this->_permisos[$key]['valor'] == 1){
                return true;
            }
        }        
        return false;
    }
    
    public function acceso($key){   
        if($this->permiso($key)){
            Session::tiempo();
            return;
        }        
        header("location:" . BASE_URL . "error/clientes/5050");
        exit;
    }*/
}

?>
