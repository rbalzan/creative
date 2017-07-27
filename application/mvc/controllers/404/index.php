<?php
class index extends Controller {

    public $model;

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view->title = 'Carrent del Caribe S.A.S.';
        $this->view->renderizar('404');
    }


}