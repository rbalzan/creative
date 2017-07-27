<?php


abstract class ViewRoutes {
	
 	static function run( $theme_active, $controller, $module ){
 		$routes = array();
 		
		if( $module ){
			$routes['view'] = PATH_MODULES . $module .DS. 'views' .DS. $controller .DS;		
		} else {
			$routes['view'] = PATH_VIEWS . $controller .DS;
		}
		
		$routes['brand'] = array(
			'url'		=> '/content/brand/' ,
			'logo'		=> '/content/brand/logo'.'.png' ,
			'favicon' 	=> '/content/brand/favicon.ico',
		);
		
		$routes['uploads'] = '/content/uploads/';
		
		$routes['assets'] = array(
			'url'		=> '/assets/',
			'js' 		=> '/assets/js/',
			'css' 		=> '/assets/css/',	
			'images' 	=> '/assets/images/',
			'components'=> '/assets/components/',
		);
		
		$routes['theme']['backend'] = array(
			'path'		=> PATH_CONTENT . 'backend' .DS ,
			'url'		=> '/content/themes/backend/' ,
			'js' 		=> '/content/themes/backend/js/',
			'css' 		=> '/content/themes/backend/css/',	
			'images' 	=> '/content/themes/backend/images/',
			'components'=> '/content/themes/backend/components/',
		);


		$routes['theme']['frontend'] = array(
			'path'		=> $theme_active ,
			'url'		=> '/content/themes/' . $theme_active ,
			'js' 		=> '/content/themes/' . $theme_active . '/js/',
			'css' 		=> '/content/themes/' . $theme_active . '/css/',	
			'images' 	=> '/content/themes/' . $theme_active . '/images/',
			'plugins' 	=> '/content/themes/' . $theme_active . '/plugins/',
		);
		return $routes;
	}
	
	
}


