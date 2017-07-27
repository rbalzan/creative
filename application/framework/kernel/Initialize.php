<?php

include_once PATH_APP . 'Conexant.php';


class Initialize {
	
	function __construct() {
		Creative::add( 'Conexant' );
		Creative::add( 'Metadata' );
		Creative::add( 'Components' );
		Creative::add( 'Hash' );
		Creative::add( 'Menus' );
		
		
		$main 		= Creative::get( 'Menus' )->add_category('Menú Principal', BACKEND);
		$admin		= Creative::get( 'Menus' )->add_category('Administración', BACKEND);
		$controle 	= Creative::get( 'Menus' )->add_category('Control de Estudio', BACKEND);
		
		
		Creative::get( 'Menus' )
			->add_menu( ''			, 'Inicio'		, ''			, 'fa fa-dashboard'	, $main)
			
			->add_menu( ''	, 'Administración'		, ''				, 'fa fa-building-o', $admin)
				->add_submenu( 'Administración'		, 'sedes'			, 'Sedes'			, 'sedes'				, 'fa fa-building-o')
				->add_submenu( 'Administración'		, 'departamentos'	, 'Departamentos'	, 'departamentos'		, 'fa fa-credit-card')
				->add_submenu( 'Administración'		, 'profiles'		, 'Perfiles'		, 'management/profiles'	, 'fa fa-vcard-o')
				->add_submenu( 'Administración'		, 'users'			, 'Usuarios'		, 'management/users'	, 'fa fa-user')
				->add_submenu( 'Administración'		, 'pagos'			, 'Pagos'			, 'pagos'				, 'fa fa-credit-card')
				
			
			->add_menu( 'carreras'	, 'Carreras'	, 'carreras'	, 'fa fa-suitcase'	, $controle)
			->add_menu( 'materias'	, 'Materias'	, 'materias'	, 'fa fa-book'		, $controle)
			->add_menu( 'estudiantes','Estudiantes'	, 'estudiantes'	, 'fa fa-users'		, $controle)
			->add_menu( 'profesores', 'Profesores'	, 'profesores'	, 'fa fa-user-secret'	, $controle)
			->add_menu( 'matriculas', 'Matriculas'	, 'matriculas'	, 'fa fa-user-secret'	, $controle);
	}
	
	
	/**
	* 
	* 
	* @return
	*/
	public function initialize(){
		
		Creative::get( 'Conexant' )->open(DB_USER, DB_PASSWORD, DB_DATABASE, DB_HOST);
		
			
		// Configuración de Themes
		$row = Creative::get( 'Conexant' )->row("SELECT * FROM configuration WHERE setting = 'ThemeActive'");
		
		if( !$row ){
			define('THEMES_ACTIVE', 'default');
		} else {
			define('THEMES_ACTIVE', $row['content']);
		}
		
		if (!defined('PATH_THEME_ACTIVE')) {
			define('PATH_THEME_ACTIVE', PATH_THEMES . THEMES_ACTIVE .DS);
		}

		if (!defined('URL_THEME')) {
			define('URL_THEME', '/content/themes/'.THEMES_ACTIVE.'/');
		}

		if (!defined('URL_THEME_ACTIVE')) {
			define('URL_THEME_ACTIVE', '/content/themes/'.THEMES_ACTIVE.'/');
		}
	
	
		//******************************************************************************************
		
		if (!defined('URL_CONTENTS_JS')) {
		    define('URL_CONTENTS_JS', '/content/themes/'.THEMES_ACTIVE.'/js/');
		}

		if (!defined('URL_CONTENTS_CSS')) {
		    define('URL_CONTENTS_CSS', '/content/themes/'.THEMES_ACTIVE.'/css/');
		}

		if (!defined('URL_CONTENTS_IMG')) {
		    define('URL_CONTENTS_IMG', '/content/themes/'.THEMES_ACTIVE.'/img/');
		}
	

		//Title Separator
		$row = $row = Creative::get( 'Conexant' )->row("SELECT * FROM configuration WHERE setting = 'TitleSeparator'");
		if( !$row ){
			define('TITLE_SEP', ' - ');
		} else {
			define('TITLE_SEP', $row['content']);
		}
		
		//Site Name
		$row = $row = Creative::get( 'Conexant' )->row("SELECT * FROM configuration WHERE setting = 'SiteName'");
		if( !$row ){
			define('SITE_NAME', 'Creative');
		} else {
			define('SITE_NAME', $row['content']);
		}
		
		//Company Name
		$row = $row = Creative::get( 'Conexant' )->row("SELECT * FROM configuration WHERE setting = 'CompanyName'");
		if( !$row ){
			define('COMPANY_NAME', 'Creative');
		} else {
			define('COMPANY_NAME', $row['content']);
		}
		
		//URL
		$row = $row = Creative::get( 'Conexant' )->row("SELECT * FROM configuration WHERE setting = 'URL'");
		if( !$row ){
			 define('URL', BASE_URL);
		} else {
			if( $row['content'] == '' or $row['content'] == NULL ){
				define('URL', BASE_URL);
			} else {
				define('URL', $row['content']);
			}
		}
		
		
		
	}
}






