<?php

if( !defined('CREATIVE') ) die('Can not access from here');

class indexController extends backendController {
	
	public function __construct() {
		parent::__construct(__CLASS__);
		$this->view->ambit( BACKEND );
		$this->view->theme( BACKEND );
		$this->view->template('template');
		$this->no_cache();
		
		$this->module = __CLASS__;
	}
	
	
	public function index() {
		$this->view->render( __FUNCTION__ );
	}
	
}

