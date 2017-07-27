<?php

/** -------------------------------------------------------
 * Creative - A PHP Framework For Web Mega Creativo
 * --------------------------------------------------------
 * 
 * @package     API Creative
 * @copyright   © 2017 Brayan Rincon
 * @version     1.0.0
 * @author      Brayan Rincon <brayan262@gmail.com>
 */



/** -------------------------------------------------------
 * Allow Méthods 
 * --------------------------------------------------------
 * Default: ['PUT', 'GET', 'POST', 'DELETE']
 */
const METHODS = ['PUT', 'GET', 'POST', 'DELETE'];

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: ' . implode(',', METHODS) );
header("Access-Control-Allow-Headers: X-Requested-With");
header("Plataform: Creative Framework");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

require_once __DIR__ . '/../../initialize.php';
require_once PATH_API . 'autoload.php';
require_once PATH_API . 'initialize.php';

Router::execute(); 

?>