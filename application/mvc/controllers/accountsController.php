<?php

class accountsController extends Controller {
	
	/**
	 * Construct
	 */
	public function __construct() {
		parent::__construct(__CLASS__);
		$this->no_cache();
	}
	

	/**
	 * Default method of the Controller
	 */
	public function index() {
		$this->auth();
	}
	

	/**
	 * Method that initiates the view to authenticate the login
	 *
	 * @return void
	 */
	public function auth( $ambit = NULL) {
		$this->view->assign( 'ambit', $ambit );
				
		$this->view->theme( BACKEND );
		$this->view->ambit( BACKEND );
					
		$this->view->template( 'auth' );
		$this->view->render( __FUNCTION__ );
	}


	public function myprofile() {
		$this->view->template = 'template.back';

		$user_id = Session::get( BACKEND )['id'];
		
		$this->model_module = $this->load_model( 'users' );
		$this->model_module->id = $user_id;
		$data = $this->model_module->find( array(
			"status_text" =>
				"CASE 
					WHEN status = 0 THEN 'Inactiva' 
					WHEN status = 1 THEN 'Activa' 
				END",
			"status_class" =>
				"CASE 
					WHEN status = 0 THEN 'danger' 
					WHEN status = 1 THEN 'success' 
				END",
			"status_info" => 
				"CASE 
					WHEN status = 0 THEN 'Carrera inactiva' 
					WHEN status = 1 THEN 'Carrera activa' 
				END",
			)
		);
		$this->view->assign('data'	, $data);
		
		
		$this->view->render( __FUNCTION__, 'index' );
	}
	
	/**
	* 
	* 
	* @return
	*/

	
	
	/**
	* 
	* 
	* @return
	*/
	public function signout(  ){
		Session::destroy(BACKEND);
		$this->location( '/accounts/');
	}
	
	
	/**
	* 
	* 
	* @return
	*/
	public function reset_password() {
		$this->view->template = 'template.login.back';
		$this->view->render( __FUNCTION__, 'index' );
	}
	
}

