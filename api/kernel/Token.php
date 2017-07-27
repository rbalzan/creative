<?php

/**
* Tiempo de Expiración del Token
*/
define('TOKEN_EXP', 10*60*10 );
define('TOKEN_PREFIX_NAME','MCTKN-');

//define('HASH_KEY', '2*WYDEG6Dd*y$6RVdUK4S&6f2');

//Session::set('Totkens', $tokens );


class Token {
	
	private $_error;
	private $_message ;
			
	/********************************************
	 * Generar un nuevo Token
	 * IP del cliente que solicita el Token
	 * Navegador del Cliente que solicita
	 * Pagina desde la cuiela se solicita
	 * 
	 * @return
	 */
	public function generate( $controller = FALSE, $exp = false, $data = array() ){
		$hash = $this->generar_hash();
		$name  = TOKEN_PREFIX_NAME . strtoupper( sha1($hash) );
		
		$token = array(
			'name' => $name,
			'data' => array(
				'hash'			=> $hash,
				'creacion'		=> time(),
				'expiracion'	=> time() + ($exp ? $exp : TOKEN_EXP),
				'controller'	=> $controller ? $controller : '',
				'navegador'		=> $_SERVER['HTTP_USER_AGENT'],
				'ipv4'			=> $_SERVER['REMOTE_ADDR'],
				'data'			=> $data
			)
		);
		$json = json_encode($token);
		$token = $this->_encode( $json );
		
		$_SESSION['csrf'][$name] = $token;
		
		return $token ;
	}
	
	
	/********************************************
	 * Generar un nuevo Token
	 * IP del cliente que solicita el Token
	 * Navegador del Cliente que solicita
	 * Pagina desde la cuiela se solicita
	 * 
	 * @return
	 *
	public static function generar_token( $controller = FALSE, $exp = false, $data = array() ){
		$tk = new Token();
		
		$new_token = array(
			'name' => TOKEN_PREFIX_NAME . sha1($tk->generar_hash()),
			'data' => array(
				'token'	=> $tk->generar_hash(),
				'fc'	=> time(),
				'fxp'	=> time() + ($exp ? $exp : TOKEN_EXP),
				'ctller'=> $controller ? $controller : '',
				'nav'	=> $_SERVER['HTTP_USER_AGENT'],
				'ipv4' => $_SERVER['REMOTE_ADDR'],
				'data'	=> $data
			)
		);
		return Token::_encode( json_encode($new_token) ) ;
	}
	*/
	
	/********************************************
	 * 
	 * @param undefined $token
	 * 
	 * @return
	 */
	public function validar( $token, $controller = '' ){
		
		if( strlen($token)<200 ){
			$this->_error = 600;
			$this->_message = 'Error de seguridad en el Token';
			return FALSE;
		}
		
		
		try{
			$token_decode = $this->_decode( $token ) ;
			$token_json = json_decode( $token_decode );
		} catch (Exception $e) {
			$this->_error = 601;
			$this->_message = 'Error de seguridad en el Token';
			return FALSE;
			
		}
		
		#Si el Token no fue recibido desde el mismo controlador enviado		
		if( $controller!='' ){
			if( $token_json->data->controller != $controller ){
				$this->_error = 602;
				$this->_message = 'Error en el Token de Seguridad';
				return FALSE;
			}
		}
		
		
		#Si no posee un nombre
		if( !isset($token_json->name) ){
			$this->_error = 603;
			$this->_message = 'Error en el Token de Seguridad';
			return FALSE;
		}	
		
		#Si no posee datos
		if( !isset($token_json->data->hash) ){
			$this->_error = 603;
			$this->_message = 'Error en el Token de Seguridad';
			return FALSE;
		}
		
		#Si no posee fecha
		if( !isset($token_json->data->creacion) ){
			$this->_error = 604;
			$this->_message = 'Error en el Token de Seguridad';
			return FALSE;
		}
		
		#Si la fecha actuál es menor a la de creación
		if( (time() < $token_json->data->creacion) ){
			$this->_error = 605;
			$this->_message = 'Error en el Token de Seguridad';
			return FALSE;
		}
		
		#Si el toekn está vencido
		if( ($token_json->data->expiracion < time()) ){
			$this->_error = 606;
			$this->_message = 'El Token Expiró';
			return FALSE;
		}
		
		
		#Si no posee datos
		if( !isset($token_json->data) ){
			
			return FALSE;
		}
		
		return TRUE;
		
	}
	
	public function get_error(){
		return array( 'code'=>$this->_error, 'message'=>$this->_message);
	}
	
	/********************************************
	 * Codificar un Estring
	 * @param undefined $string
	 * 
	 * @return
	 */
	public static function _encode( $string ){
		$hash = new Hash();
    	$encrypted = $hash->encode($string);
    	return $encrypted; //Devuelve el string encriptado
	}
	
	
	/********************************************
	 * Decodificar un String
	 * @param undefined $string
	 * 
	 * @return
	 */
	public static function _decode( $string ){
		$hash = new Hash();
    	$decrypted = $hash->decode($string);
    	return $decrypted;
	}
	
	
	
	public static function generar_hash(){
		if (function_exists("hash_algos") and in_array("sha512",hash_algos())){
			$token = hash("sha512",mt_rand(0,mt_getrandmax()));
		}else {
			$token = ' ';
			for ($i=0; $i<128; ++$i){
				$r = mt_rand(0,35);
				if ( $r<26 ){
					$c = chr(ord('a')+$r);
				}else{ 
					$c = chr( ord('0') + $r -26 );
				} 
				$token .= $c;
			}
		}
		return $token;
	}
	
	
	function input_token($form_data_html){
		$name = "MCTKNameGuard-".mt_rand(0,mt_getrandmax());
		$token = $this->generar_hash($name);
		$form_data_html = 
			'<input type="hidden" id="MCTKName" value="'.$name.'"/>'.
			'<input type="hidden" id="MCTKToken" value="'.$token.'" />';				
		return $form_data_html;
	}

}
