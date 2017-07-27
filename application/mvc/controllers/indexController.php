<?php

class indexController extends Controller
{
	
    public function __construct() {
		parent::__construct( __CLASS__ );
        $this->view->template ('template');
	}

	public function index() {

		$this->view->render( __FUNCTION__ );
	}
}

?>