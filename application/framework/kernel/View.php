<?php

require_once PATH_KERNEL . 'ViewRoutes.php';
require_once PATH_APP . 'framework/Functions.php';


/**
 * Viewer Handler
 * 
 * @copyright   © 2017 Brayan Rincon
 * @version     1.0.0
 * @author      Brayan Rincon <brayan262@gmail.com>
 */
class View extends SmartyBC 
{

	private 
		$http_meta,
		$_request,
		$_js,
		$_css,
		$_route;

	private $_module;
	private $_includes;
	private $_controller;
	private $_page;
	private $_acl;
	
	private $_template 	= 'template';
	private $_ambit 	= 'frontend';
	private $_compress 	= FALSE;
	private $_theme_active;
	
	private $_theme;

	public function __construct( Request $request, Acl $acl ) {	
		parent::__construct();
		
		$this->_request = $request;
		$this->_acl = $acl;
		
		$this->_route = array();		
		$this->_css = array();
		$this->_js = array();

		
		#Crear rutina para determinar el Tema activo
		$this->_theme = DEFAULT_THEME;
		
		//$this->_includes = PATH_THEMES . $theme_active .DS. 'includes' .DS;
		
		$this->_module			= $this->_request->get_module();		
		$this->_controller 		= $this->_request->get_controller();				
		$this->_route 			= ViewRoutes::run($this->_theme, $this->_controller, $this->_module);		
		
		$this->_route['module']	= $this->_module;
		$this->assign('acl'	, $acl);
		$this->assign('token'	, '');
	}// END: __construct
	
	
	/**
	 * Undocumented function
	 *
	 * @param string $theme
	 * @return void
	 */
	public function theme( $theme ){
		$this->_theme = strtolower($theme);
		return $this;
	}


	/**
	 * Undocumented function
	 *
	 * @param string $template
	 * @return void
	 */
	public function template( $template ){
		$this->_template = strtolower($template);
		return $this;
	}


	/**
	 * Undocumented function
	 *
	 * @param string $ambit
	 * @return void
	 */
	public function ambit( $ambit ){
		$this->_ambit 	= strtolower($ambit);
		return $this;
	}

/**
	 * Renderiza una vista (página)
	 * 
	 * @param {String} $vista Nombre de la vista (página) que se va a renderizar
	 * @param {String} $group Grupo de la vista. Ej: Departamento al cual pertence la vista. Si no se especifica por defecto será DEFAULT_LAYOUT
	 * @param {Boolean} $item Item del menú que está activo (selected)
	 * @return
	 */
	public function render($vista, $options = NULL, $callback = NULL) {
		
		$rutas 	= $this->_route;
		$css	= $this->_css;
		$js		= $this->_js;


		if( $this->_ambit == BACKEND ){
			$template_dir = PATH_CONTENT . 'themes' .DS. 'backend' . DS;
			$theme = $rutas['theme']['backend'];
		} else {
			$template_dir = PATH_CONTENT . 'themes' .DS. $this->_theme . DS;
			$theme = $rutas['theme']['frontend'];
		}
		
		
		$this->template_dir = $template_dir;
		$this->config_dir 	= PATH_CONTENT . 'themes' .DS. $this->_template . DS . 'config' . DS;
		$this->compile_dir 	= PATH_TEMPORAL . 'templates_c' . DS;
    	$this->cache_dir 	= PATH_TEMPORAL . 'cache' . DS;

        /*if( ENVIRONMENT == 'development' )
			$this->debugging = TRUE;	*/
		
			
		if( strpos($vista, '/') === false ){
			$vista		= $vista ? $vista : DEFAULT_VIEW;
			$path_view	= $rutas['view'] . $vista . '.tpl';
		} else {
			$vista		= $vista ? $vista : DEFAULT_VIEW;
			$path_view 	= PATH_VIEWS . $vista . '.tpl';
		}
		

		
		#Verificar que el archivo exista y se pueda leer
		if (is_readable($path_view)) {
			$this->assign('_html', $path_view);
		} else {
			ErrorHandler::run_exception( 'View Not Found: [' . $path_view. ']' );
		}
		
		
		
		$this->assign('uploads'		, $rutas['uploads']);
		$this->assign('brand'		, $rutas['brand']);
		$this->assign('favicon'		, $rutas['brand']['favicon']);
		$this->assign('lang'		, DEFAULT_LANG);		
		$this->assign('theme'		, $theme);
		$this->assign('assets'		, $rutas['assets']);
		$this->assign('angular'		, false);
		$this->assign('echo'		, $GLOBALS['CREATIVE']['echo']);
		$this->assign('app'			, $GLOBALS['CREATIVE']['CONF']['app']);

		$this->assign('breadcrumbs'	, false);
		/**$this->assign('menus'	, array(
			'category' 	=> Creative::get( 'Menus' )->get_category(),
			'menus'		=> Creative::get( 'Menus' )->get_menu()
		));
		*/
		
		if( $this->_compress ) $this->init_compress();
		
		$this->display($this->_template.'.tpl');
		
		if( $callback ) $callback();
		
		if( $this->_compress ) $this->end_compress();
		
			
    }
	
	
	
	
	/**
	 * Renderiza una vista (página)
	 * 
	 * @param {String} $vista Nombre de la vista (página) que se va a renderizar
	 * @param {String} $group Grupo de la vista. Ej: Departamento al cual pertence la vista. Si no se especifica por defecto será DEFAULT_LAYOUT
	 * @param {Boolean} $item Item del menú que está activo (selected)
	 * @return
	 */
	public function render_error( $error ) {
		
		if( $this->_ambit == BACKEND ){
			$template_dir = PATH_CONTENT . 'themes' .DS. 'backend' . DS;
		} else {
			$template_dir = PATH_CONTENT . 'themes' .DS. $this->_theme . DS;
		}
		
		
		$this->template_dir = $template_dir;
		$this->config_dir 	= PATH_CONTENT . 'themes' .DS. $this->_template . DS . 'config' . DS;
		$this->compile_dir 	= PATH_TEMPORAL . 'templates_c' . DS;
    	$this->cache_dir 	= PATH_TEMPORAL . 'cache' . DS;
    	
               		
		$rutas 	= $this->_route;
		$css	= $this->_css;
		$js		= $this->_js;
		$page 	= array('js'=>$js,'css'=>$css);
		
		$vista			= $error ? $error : DEFAULT_VIEW;
		$path_view 	 	= PATH_VIEWS .'_errors'.DS. $vista . '.tpl';
		
		$page = array('js'=>$js,'css'=>$css);
		
		if (is_readable($path_view)) {			
			$this->assign('_html', $path_view);
		} else {			
			$out  = '<strong style="color:red"> View Not Found <i>"'.$path_view.'"</i></strong><br/><br/>';
			$out .= '<strong>File:</strong> '.__FILE__.'<br/>';
			$out .= '<strong>Method:</strong> '. __FUNCTION__ .'<br/>';
			$out .= '<strong>Line:</strong> '.__LINE__;	
			ErrorHandler::run_exception( 'View Not Found: [' . $vista. ']' );	
				
			//CreativeBase::run_exception('View Not Found', $out);
		}
				
		$this->assign('favicon'		, $rutas['brand']['favicon']);
		$this->assign('lang'		, DEFAULT_LANG);		
		$this->assign('theme'		, $rutas['theme']);
		$this->assign('assets'		, $rutas['assets']);		
		
		
		if( $this->_compress ) $this->init_compress();		
		$this->display('errors.tpl');
		if( $this->_compress ) $this->end_compress();
    }
	
	
	
	private function init_compress(){
		ob_start(function ($buffer){
			$search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
			$replace = array('>','<','\\1');					
			return preg_replace($search, $replace, $buffer);					
		});
	}
	
	private function end_compress(  ){
		ob_end_flush();
	}
	
	
	
	
	
	/**
	* 
	* @param undefined $html
	* 
	* @return
	*/
	public function generate( $html ) {
		
		$modulo = $this->rutas['modulo'];
	
		$this->template_dir = PATH_THEMES . $this->_template . DS;
		$this->config_dir 	= PATH_THEMES . $this->_template . DS . 'config' . DS;
		$this->compile_dir 	= PATH_TEMPORAL . 'templates_c' . DS;
    	$this->cache_dir 	= PATH_TEMPORAL . 'cache' . DS;
		
		$rutas 	= $this->rutas;
		$css	= $this->css;
		$js		= $this->js;
		
		$this->assign('_html', $html['contenido']);
		
		$this->assign('titulo'		, $html['titulo']);
		$this->assign('BASE_URL'	, BASE_URL);
		$this->assign('name'		, '');
		$this->assign('resources'	, $rutas);
		$this->assign('brand'		, $resources['includes']['brand'].'logo.png');
		$this->assign('favicon'		, $resources['includes']['brand'].'favicon.ico');
		$this->assign('lang'		, DEFAULT_LANG);
		
		$this->assign('themes'	, $resources['themes']);
		$this->assign('view'	, $resources['view']);
		$this->assign('includes', $resources['includes']);
		
		$this->assign('page', $page);
		
		$this->display('template.tpl');
				
		
    }
	
		
	#------------------------------------------------------------------------------------------------------------------------------
	
	/**
	* Agrega un nuevo CSS proveniente de la vista
	* @author Brayan Rincon
	* 
	* @author Brayan Rincon	
	* @param undefined $css Nombre del CSS
	* @param undefined $in_head Si es TRUE el CSS se agrega en el head, si es FASLE se agrega en el footer. Por defecto es TRUE
	* 
	* @since 2.0
	* @return
	* 
	*/
	public function add_view_css( $css, $in_head = TRUE, $media = FALSE ){
		$this->css[] = array(
			'src'		=> $this->rutas['view']['css'] . $css,
			'in_head'	=> $in_head,
			'media'		=> $media
		);
		return TRUE;
	}
	
	
	/**
	* Agrega un nuevo JS proveniente de la vista
	* @author Brayan Rincon
	* 
	* @param string $js Archivo JS
	* @param bool $in_head Indica si el archivo debe incluirse en el head
	* @param string $load Se ejecuta en cuanto la descarga del JS se ha completado. Posibles valores: [asyn|defer|FALSE]
	* 
	* @since 2.0
	* @return
	*/
	public function add_view_js( $js, $in_head = TRUE, $load = FALSE ){
		$this->js[] = array(
			'src'		=> $this->rutas['view']['js'] . $js,
			'in_head'	=> $in_head,
			'load'		=> $load
		);	
		return TRUE;
	}

	#------------------------------------------------------------------------------------------------------------------------------
	
		
	/**
	* Agrega un nuevo CSS proveniente del tema actual
	* @author Brayan Rincon
	* 
	* @param string $css Nombre del CSS
	* @param bool $in_head Si es TRUE el CSS se agrega en el head, si es FASLE se agrega en el footer. Por defecto es TRUE
	* 
	* @since 2.0
	* @return
	*/
	public function add_theme_css( $css, $in_head = TRUE ){
		$this->_css[] = array(
			'src'		=> $this->_route['theme']['url'] . $css ,
			'in_head'	=> $in_head
		);
		return TRUE;
	}
	
	
	/**
	* Agrega un nuevo JS proveniente del tema actual
	* @author Brayan Rincon
	* 
	* Agrega un CSS del Tema actual
	* @param undefined $css Nombre del CSS
	* @param undefined $in_head Si es TRUE el CSS se agrega en el head, si es FASLE se agrega en el footer. Por defecto es TRUE
	* @param string $load Se ejecuta en cuanto la descarga del JS se ha completado. Posibles valores: [asyn|defer|FALSE]
	* 
	* @since 2.0
	* @return
	*/
	public function add_theme_js( $js, $in_head = TRUE, $load = FALSE ){
		$this->_js[] = array(
			'src'		=> $this->_route['theme']['url'] . $js ,
			'in_head'	=> $in_head,
			'load'		=> $load ? $load :  ''
		);
		return TRUE;
	}
	
	#------------------------------------------------------------------------------------------------------------------------------

	/**
	* Agrega un nuevo CSS del tema actual
	* @author Brayan Rincon
	* 
	* Agrega un CSS del Tema actual
	* @param undefined $css Nombre del CSS
	* @param undefined $in_head Si es TRUE el CSS se agrega en el head, si es FASLE se agrega en el footer. Por defecto es TRUE
	* 
	* @return
	*/
	public function add_asset_css( $css, $in_head = TRUE ){
		$this->_css[] = array(
			'src'		=> $this->_route['asset']['url'] . $css ,
			'in_head'	=> $in_head
		);
	}
	
	public function add_asset_js( $js, $in_head = TRUE, $load = FALSE ){
		$this->_js[] = array(
			'src'		=> $this->_route['asset']['url'] . $js,
			'in_head'	=> $in_head,
			'load'		=> $load ? $load :  ''
		);
	}
	
	#------------------------------------------------------------------------------------------------------------------------------
	
	public function add_js( $js, $in_head = TRUE, $asyn = FALSE, $load = 'defer' ){
		$this->_js[] = array(
			'src'		=> $js,
			'in_head'	=> $in_head,
			'load'		=> $load ? $load :  ''
		);
	}
	
	public function add_css( $css, $in_head = TRUE, $asyn = FALSE, $load = 'defer' ){
		$this->_css[] = array(
			'src'		=> $css ,
			'in_head'	=> $in_head
		);
	}
	
	
	#------------------------------------------------------------------------------------------------------------------------------
	
		
	/*private function crumbs( $href, $text, $title, $i, $is_link = true){
		if ($is_link){				
			return sprintf('<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
												<a itemprop="item" href="%s" title="%s"><span itemprop="name">%s</span></a>
												<meta itemprop="position" content="%d" />
											</li>'
										, $href, $text, $title, $i);
		}	else {
			return sprintf('<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="active">
												<span itemprop="name">%s</span>
												<meta itemprop="position" content="%d" />
											</li>'
										, $text, $title, $i);
		}
	}
		
	public function breadcrumbs($separator = ' ›› ', $home = 'Inicio') {
	    $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));			
		if (count($path)<1){return;}
			
	    $base = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
		$i = 1;
	    $breadcrumbs = array(
				$this->crumbs($base, $title, '<span class="icon-home"></span> '.$home, $i)
			);
	 		
			$plan = $this->instance->get_plan('url_info',$this->plan);
	    $last = end(array_keys($path));
	 		
	    foreach ($path as $x => $crumb) {
	        $link_text = ucwords(str_replace(array('.php', '_'), array('', ' '), $crumb));
	 				$href = $base . $crumb;
					
	        if ($x != $last) {
	            $breadcrumbs[] = $this->crumbs($href, $link_text, $link_text, $i);
	        } else {
						if ( $plan==NULL ){
							//$breadcrumbs[] = $this->crumbs($href, '', $link_text, $i, FALSE);						
						} else {
							$breadcrumbs[] = $this->crumbs($href, $plan['descrip_corta'], $plan['descrip_corta'] , $i, FALSE);
						} 
	        }
					
					$i++;
	    }
	 
	    return '<div class="well-breadcrumb">
								<div class="container">
								<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">' .
									implode($separator, $breadcrumbs) . 
							 '</ol>
							  </div>
							</div>
						';
	}
	
		*/
	public function widget($widget, $method, $options = array()){
        if(!is_array($options)){
            $options = array($options);
        }
        
        if(is_readable(ROOT . 'widgets' . DS . $widget . '.php')){
            include_once ROOT . 'widgets' . DS . $widget . '.php';
            
            $widgetClass = $widget . 'Widget';
            
            if(!class_exists($widgetClass)){
                throw new Exception('Error clase widget');
            }
            
            if(is_callable($widgetClass, $method)){
                if(count($options)){
                    return call_user_func_array(array(new $widgetClass, $method), $options);
                }
                else{
                    return call_user_func(array(new $widgetClass, $method));
                }
            }
            
            throw new Exception('Error metodo widget');
        }
        
        throw new Exception('Error de widget');
    }
            
    private function getWidgets(){
        $widgets = array(
            'menu-sidebar' => array(
                'config' => $this->widget('menu', 'getConfig'),
                'content' => array('menu', 'getMenu')
            )
        );
        
        $positions = $this->getLayoutPositions();
        $keys = array_keys($widgets);
        
        foreach($keys as $k){
            /* verificar si la posicion del widget esta presente */
            if(isset($positions[$widgets[$k]['config']['position']])){
                /* verificar si esta deshabilitado para la vista */ 
                if(!isset($widgets[$k]['config']['hide']) || !in_array($this->_item, $widgets[$k]['config']['hide'])){
                    /* verificar si esta habilitado para la vista */
                    if($widgets[$k]['config']['show'] === 'all' || in_array($this->_item, $widgets[$k]['config']['show'])){
                        /* llenar la posicion del layout */
                        $positions[$widgets[$k]['config']['position']][] = $this->getWidgetContent($widgets[$k]['content']);
                    }
                }
            }
        }
        
        return $positions;
    }
    
    public function getWidgetContent(array $content){
        if(!isset($content[0]) || !isset($content[1])){
            throw new Exception('Error contenido widget');
            return;
        }
        
        if(!isset($content[2])){
            $content[2] = array();
        }
        
        return $this->widget($content[0],$content[1],$content[2]);
    }

	
}


