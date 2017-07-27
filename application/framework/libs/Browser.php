<?php

class Browser {
	
	private $navegadores = array(
		//opera
		'Opera' 		=> 'Opera',
		'Opera Mini' 	=> 'Opera Mini',
		
		//Otros
		'Web TV'		=> 'WebTV',
		'Omni Web' 		=> 'OmniWeb',
		'Galeon' 		=> 'Galeon',
		'MyIE'			=> 'MyIE',
		'Lynx'			=> 'Lynx',
		'Netscape' 		=> 'Netscape',
		'Vivaldi'		=> 'Vivaldi',
    	'Dragon'		=> 'Dragon',
    	'Yandex'		=> 'Yandex',
    	'Amaya'			=> 'Amaya',
    	'PlayStation'	=> 'PlayStation',
    	
    	//Google
    	'Chrome'	=> '(Chrome)|(WebKit)',
    	'GoogleBot'	=> 'GoogleBot',
    	
    	//Mac
    	'iPhone'	=> 'iPhone',
    	'iPod'		=> 'iPod',
    	'iPad'		=> 'iPad',
    	'Safari'	=> 'Safari',
    	
    	//Mozilla
    	'Mozilla Firefox'	=> '(Firebird)|(Firefox)',
    	'Mozilla'			=> 'Gecko',
    	'Iceweasel'			=> 'Iceweasel',
    	'Shiretoko'			=> 'Shiretoko',
    	'Konqueror'		=> 'Konqueror',
    	
    	
		//Mobiles
		'Android'		=> 'Android',
		'BlackBerry'	=> 'BlackBerry',
		'Nokia'			=> 'Nokia',
		'Nokia Browser'	=> 'Nokia Browser',
		'Nokia S60'		=> 'Nokia S60 OSS Browser',
		'Gsa'			=> 'Gsa',
		
		//Internet Explorer
		'Internet Explorer 7' 	=> '(MSIE 7\.[0-9]+)',
		'Internet Explorer 6' 	=> '(MSIE 6\.[0-9]+)',
		'Internet Explorer 5' 	=> '(MSIE 5\.[0-9]+)',
		'Internet Explorer 4'	=> '(MSIE 4\.[0-9]+)',			
      	'Microsoft Edge'		=> 'Edge',
      	'Microsoft Trident' 	=> 'Trident',
      	'Pocket Internet Explorer' => 'Pocket Internet Explorer', 
		
	), $sistemas_operativos = array(
		'Windows',
		'Windows CE',
		'Apple',
		'OS/2',
		'BeOS',
		'iPhone',
		'iPod',
		'iPad',
		'BlackBerry',
		'Nokia',
		'FreeBSD',
		'OpenBSD',
		'NetBSD',
		'SunOS',
		'OpenSolaris',
		'Android',
		'Sony PlayStation',
		'Mac',
		'Linux'
	);
		
	public function __construct($user_agent = ""){
        $this->reset();
        if ($user_agent != "") {
            $this->set_user_agent( $user_agent );
        } else {
            $this->detectar();
        }
    }
    
    
    public function get_browser(){
		# devolvemos el array de valores
		$browser = array(
	        'agent' 		=> $this->agent,
	        'browser_name'	=> $this->browser_name,
	        'version'	=>$this->version,
	        'platform'	=>$this->platform,
	        'os'		=>$this->os,
	        'is_mobile'	=>$this->is_mobile,
	        'is_tablet'	=>$this->is_tablet,
	        'is_robot'	=>$this->is_robot,
		);

		return $browser;
	}
    /**
     * Set the user agent value (the construction will use the HTTP header value - this will overwrite it)
     * @param string $agent_string The value for the User Agent
     */
    public function set_user_agent( $user_agent ){
        $this->reset();
        $this->agent = $user_agent;
        $this->detectar();
    }
    
    /**
	* Reinicia todas las propiedades
	* 
	* @return
	*/
	public function reset(){
        $this->agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
        $this->browser_name = '';
        $this->version = '';
        $this->platform = '';
        $this->os = '';
        $this->is_aol = false;
        $this->is_mobile = false;
        $this->is_tablet = false;
        $this->is_robot = false;
        $this->is_facebook = false;
        $this->aol_version = '';
    }

	/**
	* 
	* 
	* @return
	*/
	public function detectar()																																																										{
		
		$user_agent = $this->agent;
		
		
		# buscamos el navegador con su sistema operativo
		foreach($this->navegadores as $navegador => $value) {
			
			if( preg_match("/".$value."/i", strtolower($user_agent)) ){
				$this->browser_name = $navegador;
				
				if ( stripos($user_agent, 'Android') !== false) {
                    if (stripos($user_agent, 'Mobile') !== false) {
                        $this->set_mobile();
                    } else {
                        $this->set_tablet();
                    }
                }
                
                //Versión
                $nav = explode('/', stristr($user_agent, $navegador));
				if( isset($nav[1]) ) {
					$ver = explode(" ", $nav[1]);
					if( is_array($ver) ) $ver = $ver[0];
					$this->set_version( str_replace(";", "", $ver) );
				}
				
                $mobiles = array(
					'Android',
					'BlackBerry',
					'Nokia',
					'Nokia Browser',
					'Nokia S60',
					'Gsa',
                );
                
                if( preg_grep("/".$navegador."/i", $mobiles) or stripos($user_agent, 'Mobile') !== false ){
					$this->set_mobile();
				}
                
                break;
			}
			
		
		}
	 
		# obtenemos el sistema operativo
		foreach($this->sistemas_operativos as $key => $value) {
			if (strpos(strtoupper($user_agent), strtoupper($value) )!==false){
				$this->os = $value;
				break;
			}
		}

	}

	/**
     * Set the version of the browser
     * @param string $version The version of the Browser
     */
    public function set_version($version) {
        $this->version = preg_replace('/[^0-9,.,a-z,A-Z-]/', '', $version);
    }
    
	/**
	* Set the Browser to be mobile
	* @param boolean $value is the browser a mobile browser or not
	*/
	protected function set_mobile($value = true){
		$this->is_mobile = $value;
	}
	/**
	* Set the Browser to be tablet
	* @param boolean $value is the browser a tablet browser or not
	*/
	protected function set_tablet($value = true){
		$this->is_tablet = $value;
	}

    
}

?>