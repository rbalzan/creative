<?php

class Hash {
    public static function get_hash( $algoritmo, $data, $key ){
        $hash = hash_init($algoritmo, HASH_HMAC, $key);
        hash_update($hash, $data);
        
        return hash_final($hash);
    }
    
    /********************************************
	 * Codificar un Estring
	 * @param undefined $string
	 * 
	 * @return
	 */
	public function encode( $string ){
    	$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(HASH_KEY), $string, MCRYPT_MODE_CBC, md5(md5(HASH_KEY))));
    	return $encrypted; //Devuelve el string encriptado
	}
	
	
	/********************************************
	 * Decodificar un String
	 * @param undefined $string
	 * 
	 * @return
	 */
	public function decode( $string ){
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(HASH_KEY), base64_decode($string), MCRYPT_MODE_CBC, md5(md5(HASH_KEY))), "\0");
		return $decrypted;
	}
}

