<?php

abstract class Net{
    
	public static function ipaddress_client(){
		$localhost = "127.0.0.1"; $ip="";
		if ($_SERVER) {
			if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {$ip= $_SERVER['REMOTE_ADDR'];}
			return ($ip = "::1" ? $localhost : $ip);
		} else {
			if (getenv('HTTP_CLIENT_IP'))
				return getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				return getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				return getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				return getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
				return getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				return getenv('REMOTE_ADDR');
			else
				return 'UNKNOWN';
		}
	}
}

?>