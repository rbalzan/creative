<?php

if( !defined("CREATIVE") ) die();

class @nameController extends Controller{
	
    public function __construct() {

		parent::__construct( __CLASS__ );

		/**
		* Default template in which views are rendered
		*/
        $this->view->template ("template");

		/**
		* This global variable saves an instance 
		* in a table that matches the class name
		*/
		$this->model_base = $this->load_model('@name');

		/**
		* Avoid caching
		*/
        $this->no_cache();
	}

	/**
	* Default method of the Controller
	*/
	public function index() {

		/**
		* This method renders a view
		*/
		$this->view->render( __FUNCTION__ );
	}
}
?>