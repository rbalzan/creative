<?php

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


/*if (!defined('PATH_CONTROLLERS')){
	define('PATH_CONTROLLERS', ROOT .DS. VERSION .DS. 'controllers' .DS);
}
*/
if (!defined('DEFAULT_CONTROLLER')){
	define('DEFAULT_CONTROLLER', 'index');
}

if (!defined('DEFAULT_METHOD')){
	define('DEFAULT_METHOD', 'index');
}


if (!defined('BACKEND')) {
    define('BACKEND', 'backend');
}	
if (!defined('FRONTEND')) {
    define('FRONTEND', 'frontend');
}	

/**
* Directorio temporal del sistema
*/

/**
* Directorio de ubicación de las sesiones
*/

define('PATH_VERSIONS', PATH_API . 'versions' . DS);

define('PATH_TEMPORAL', PATH_APP . 'temporal' . DS);

define('PATH_SESSION', PATH_TEMPORAL . 'session' . DS);
