<?php

class ModelGenerator extends Model {
	
	public function __construct( $table, $id = 'id' ){
		$this->table = $table;
		$this->pk = $id;
		parent::__construct();
	}
	
}
