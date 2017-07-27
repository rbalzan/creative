<?php

define("CREATIVE", TRUE);

define('CREATIVE_START', microtime(true));

define('CREATIVE_MINIMUM_PHP', '5.6');

if (version_compare(PHP_VERSION, CREATIVE_MINIMUM_PHP, '<')){
	die('Your host needs to use PHP ' . CREATIVE_MINIMUM_PHP . ' or higher to run this version of Creative Framework!');
}


define('DS', DIRECTORY_SEPARATOR);

define('PATH_ROOT', realpath(dirname(__FILE__)) . DS);

    

    define('PATH_APP', PATH_ROOT . 'application' .DS);
        
        define('PATH_FRAMEWORK', PATH_APP . 'framework' .DS);
            
            define('PATH_KERNEL', PATH_FRAMEWORK .DS. 'kernel' .DS);

            define('PATH_CONF', PATH_APP .DS. 'conf' .DS);



if( !file_exists(PATH_APP . DS . 'settings.json') ){
    echo "<strong>Environment Settings File NOT Found:</strong> " . PATH_APP . DS. 'settings.json';
    exit;
}

require_once PATH_APP . 'framework/SettingsAnalyzer.php';

SettingsAnalyzer::execute();


    define('PATH_PUBLIC_HTML', PATH_ROOT . PUBLIC_HTML .DS);

    define('PATH_API', PATH_ROOT . 'api' .DS);


    $GLOBALS['CREATIVE']['CONF'] = array();

    

?>