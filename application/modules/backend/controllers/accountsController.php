<?php

if( !defined('CREATIVE') ) die('Can not access from here');

class accountsController extends backendController {
	
	public function __construct() {
		parent::__construct();
		$this->view->template = 'template.back';
		$this->no_cache();
		
		$this->module = __CLASS__;
	}
	
	
	public function index() {
		$this->signin();
	}
	
	public function signin() {
		$this->view->template = 'template.login.back';
		$this->view->render( __FUNCTION__, 'index' );
	}
	
	public function signout(  ){
		Session::destroy(BACKEND);
		$this->location( '/' . BACKEND . '/accounts/');
	}
	
	public function reset_password() {
		$this->view->template = 'template.login.back';
		$this->view->render( __FUNCTION__, 'index' );
	}
	
}

