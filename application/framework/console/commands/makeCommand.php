<?php

/*
* controller
* model
* view

ambit: guest, frontend, backend
*/
class makeCommand extends Console {
    
    private
        $_args,
        $_options,
        $_params;
    
    /**
     * 
     **/
    public function execute(array $args, array $options = array()){
        
        $method	= strtolower(array_shift($args));
        $this->_args = $args;
        $this->_options = $options;
     
        $this->_params = array();
        $i = 0;
        foreach ($this->_options as $key => $value) {
            if( isset($this->_args[$i]) ) {
                $this->_params[$key] = $this->_args[$i];
                $i++;
            } else $this->_params[$key] = '0';
        }

        call_user_func(array($this, $method));
    }

    
    public function controller( ){
        
        $name   = 'web';
        $ambit  = 'guest';
        $force  = '0';

        //print_r($this->_params);exit;
        if( isset($this->_params['name']) ){
            $name = $this->_params['name'];
        }

        if( isset($this->_params['ambit']) ){
            $ambit = $this->_params['ambit'];
        }
        
        if( isset($this->_options['force']) ){
            $force = $this->_options['force'];
        }

        
        //print_r($this->_args);print_r($this->_options);exit;
        $controller_file_name = PATH_ROOT.'application/mvc/controllers/'. $name . 'Controller.php';
        $view_file_name = PATH_ROOT.'application/mvc/views/'. $name . '/index.tpl';

        if( file_exists($controller_file_name) AND $force == '0' ){
            $this->dialog = new ConsoleKit\Widgets\Dialog($this->console);
            if ( !$this->dialog->confirm("The controller [{$name}] already exists, you want to rewrite it?")) {
               $this->terminate(409, "The controller already exists. If you want to replace it with an empty controller, the '--force' parameter to force creation");
            } else {
                $force = '1';
            }
        }


        $content_controller = $this->get_template(__FUNCTION__);
        $content_controller = str_ireplace( '@name', $name, $content_controller);
        $this->write_file( $controller_file_name, $content_controller );

        if( !file_exists (PATH_ROOT.'application/mvc/views/'. $name) ) 
            mkdir( PATH_ROOT.'application/mvc/views/'. $name , 0700 );

        if( file_exists($view_file_name) ){
            $this->dialog = new ConsoleKit\Widgets\Dialog($this->console);
            if ( $this->dialog->confirm("The view [{$name}] already exists, you want to rewrite it?")) {
               
                $content_view = $this->get_template('view');
                $content_view = str_ireplace( '@name', $name, $content_view);
                $this->write_file( $view_file_name, $content_view );

            }
        } else {
            $content_view = $this->get_template('view');
            $content_view = str_ireplace( '@name', $name, $content_view);
            $this->write_file( $view_file_name, $content_view );
        }

        if( $force == '1' ){
            $this->terminate(405, "Controller [{$name}] has rewrite");
        } else {
            $this->terminate(201);
        }
        
    }




    public function view( ){
        
        $name   = 'web';
        $controller = 'web';
  
        //print_r($this->_params);exit;
        if( isset($this->_params['name']) ){
            $name = $this->_params['name'];
        }

        
        if( isset($this->_params['controller']) ){
            $controller = $this->_params['controller'];
        }

        $view_file_name = PATH_ROOT."application/mvc/views/{$controller}/{$name}.tpl";

        if( file_exists($view_file_name) ){
            $this->dialog = new ConsoleKit\Widgets\Dialog($this->console);
            if ( $this->dialog->confirm("The view [{$name}] already exists, you want to rewrite it?")) {
               
                $content_view = $this->get_template('view');
                $content_view = str_ireplace( '@name', $name, $content_view);
                $this->write_file( $view_file_name, $content_view );
                $this->terminate(405, "View [{$name}] has rewrite");
            }
        } else {
            if( !file_exists (PATH_ROOT."application/mvc/views/{$controller}") ) 
                mkdir( PATH_ROOT."application/mvc/views/{$controller}" , 0700 );

            $content_view = $this->get_template('view');
            $content_view = str_ireplace( '@name', $name, $content_view);
            $this->write_file( $view_file_name, $content_view );
            $this->terminate(201);
        }
       
    }

}

/*class makeCommand extends Commands {

    public function run( $action ){
        $args = func_get_args();
        $action = strtolower(array_shift($args));
        call_user_func_array(array($this, $action), $args);
    }


    public function controller( $name, $args = NULL){

        $controller_file_name = PATH_ROOT.'application/mvc/controllers/'. $name . 'Controller.php';
        
        if( file_exists($controller_file_name) AND $args != '-f'){
            $this->terminate(409, "The Controller already exists. If you want to replace it with an empty controller, the '-f' parameter to force creation");
        }

        $path_tpl = __DIR__ . '/templates/'.__FUNCTION__.'.tpl';
        $content = '';

        if (file_exists($path_tpl)){
			$file = fopen($path_tpl, 'r');
			while(!feof($file)) {
				$content .= fgets($file);
			}
			fclose($file);
		} else {
			die( 'Error' );
		}
        
        $content = str_ireplace( '@name', $name, $content);

        $controller_file = fopen( $controller_file_name,'w' );
        fwrite($controller_file, $content);
        fclose($controller_file);

        if( $args != '-f' ){
            $this->terminate(201);
        } else {
            $this->terminate(405, "Controller {$name} has reset");
        }
        
    }

    
}*/
?>