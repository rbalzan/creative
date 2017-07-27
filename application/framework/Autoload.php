<?php
/**
 * Función de autoload
 * Se utiliza para incluir Componetes del Kernel
 * @param $file string el Nombre de la clase
 */ 
function autoload_kernel( $file ) {
	if(file_exists(PATH_KERNEL . ucfirst(strtolower($file)) . '.php')){
		include_once(PATH_KERNEL . $file . '.php' );
	}
	if(file_exists(PATH_KERNEL . 'sessions' .DS. ucfirst(strtolower($file)) . '.php')){
		include_once(PATH_KERNEL . 'sessions' .DS. $file . '.php' );
	}
}
spl_autoload_register('autoload_kernel');



/**
 * Función de autoload
 * Se utiliza para incluir Libreías
 * @param $file string el Nombre de la clase
 */ 
function autoload_libs( $file ){ 
	if(file_exists(PATH_LIBS . $file .DS. strtolower($file).'.php')){
        include_once PATH_LIBS . $file .DS. strtolower($file). '.php';
    }
    if(file_exists(PATH_LIBS .  strtolower($file) .DS. strtolower($file).'.class.php')){
        include_once PATH_LIBS . strtolower($file)  .DS. strtolower($file) .'.class.php';
    }
}
spl_autoload_register("autoload_libs");




/**
 * Función de autoload
 * Se utiliza para incluir Interfaces
 * @param $file string el Nombre de la clase
 */ 
function autoload_interfaces( $name ) {
	if(file_exists( PATH_KERNEL .DS. 'interfaces' .DS. $name . '.php')){
		include_once( PATH_KERNEL .DS. 'interfaces' .DS. $name . '.php' );
	}
}
spl_autoload_register('autoload_interfaces');



/**
 * Función de autoload
 * Se utiliza para incluir Controladores
 * @param $file string el Nombre de la clase
 */ 
function autoload_controllers($ClassName) {
	if(file_exists(PATH_CONTROLLERS . $ClassName . '.php')){
		include_once(PATH_CONTROLLERS . $ClassName . '.php' );
	}
}
spl_autoload_register('autoload_controllers');