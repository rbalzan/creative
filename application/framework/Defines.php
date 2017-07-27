<?php

#-------------------------------- Dominio Principal --------------------------------

/**
* Indica si el sitio usa Certificados de Seguridad SSL
*/
//define('USE_SSL', FALSE);

$GLOBALS['CREATIVE'] = array(
	'echo' => array()
);

/**
* 
* @var Devuelve el Dominio actual del Sitio
* 
*/
if (!defined('DOMAIN')) {
	$host = $_SERVER['SERVER_NAME'] ;
	define('DOMAIN', $host);
}

/**
* 
* @var Defina la URL BASE del Sitio
* 
*/
if (!defined('BASE_URL')) {
	
	//Verificar si tiene Certificado SSL
	$protol = USE_SSL ? 'https://' : 'http://';
	
	//Puerto de escucha
	$port = ($_SERVER['SERVER_PORT']=='80' ? '' : ':'.$_SERVER['SERVER_PORT'] );
	
	$host = $protol.DOMAIN.$port .'/' ;
	
	define('BASE_URL', $host);
}


/************************************* Core/Kernel *************************************
* 
* Directorios y URL del Core/Kernel
* 
****************************************************************************************/
	/**
	*  Directorio de cargador del MVC (Controladores)
	*/
	define('PATH_CONTROLLERS', PATH_APP . 'mvc'.DS.'controllers' .DS);
	
	
	/**
	* Directorio de cargador del MVC (Modelos)
	*/

	define('PATH_MODELS', PATH_APP . 'mvc'.DS.'models' .DS);
	
	
	/**
	* Directorio de cargador del MVC (Vistas)
	*/
	define('PATH_VIEWS', PATH_APP . 'mvc'.DS.'views' .DS);
	
	
	/**
	* Directorio de librerías AddOn
	*/
	define('PATH_LIBS', PATH_FRAMEWORK . 'libs' .DS);
	
	
	/**
	* Directorio de Módulos del Sistema
	*/
	define('PATH_MODULES', PATH_APP . 'modules' . DS);
	
	
	/**
	* Directorio temporal del sistema
	*/
	define('PATH_TEMPORAL', PATH_APP . 'temporal' . DS);
	
	
	/**
	* Directorio de ubicación de las sesiones
	*/
	define('PATH_SESSION', PATH_TEMPORAL . 'session' .DS);
	
	
	define('PATH_CONTENT', PATH_PUBLIC_HTML . 'content' . DS);


	/**
	* Define el controlador por defecto del cargardor
	*/
	define('DEFAULT_CONTROLLER', 'index');	
	
	/**
	* Define el controlador por defecto del cargardor
	*/
	define('DEFAULT_METHOD', 'index');	
	
	/**
	* Define el controlador por defecto del cargardor
	*/
	define('DEFAULT_THEME', 'default');	
	
	/**
	* Define el controlador por defecto del cargardor
	*/
	define('DEFAULT_VIEW', 'index');

    
	
if (!defined('BACKEND')) {
    define('BACKEND', 'backend');
}	
if (!defined('FRONTEND')) {
    define('FRONTEND', 'frontend');
}