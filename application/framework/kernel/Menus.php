<?php

class Menus {
	
	private 
		$_menus = array(),
		$_categories = array(FRONTEND => array(), BACKEND=> array());
	
	
 	public function add_menu( $table, $name, $url, $icon , $category = 0 ){
 		
 		$ambit = strpos( $url, BACKEND) ? BACKEND : FRONTEND;
 		
		$this->_menus[strtolower($name)] = array(
			'name'		=> strtolower($name),
			'url' 		=> $url,
			'title' 	=> $name,
			'ambit'		=> $ambit,
			'category' 	=> $category,
			'parent'	=> NULL,	
			'icon'		=> $icon ? $icon : 'fa fa-circle',
			'table'		=> $table ? $table : 'dashboard',
		);
		
		return $this;
	}
	
	
	 public function add_submenu( $parent, $table, $name, $url, $icon ){
	 	
 		$ambit = strpos( $url, BACKEND) ? BACKEND : FRONTEND;
 		
		$this->_menus[strtolower($parent)]['childs'][] = array(
			'name'		=> strtolower($name),
			'url' 		=> $url,
			'title' 	=> $name,
			'ambit'		=> $ambit,
			'parent'	=> $parent,	
			'icon'		=> $icon ? $icon : 'fa fa-circle',
			'table'		=> $table ? $table : 'dashboard',
		);
		return $this;
	}
	
	
	
	public function add_category( $category, $ambit = FRONTEND ){
		$this->_categories[$ambit][] = $category;
		return (count($this->_categories[$ambit])-1);
	}
	
	public function render_menu(){
		return $this->_categories;
	}
	
	
	public function get_menu(){
		return $this->_menus;
	}
	
	public function get_category(){
		return $this->_categories;
	}
	
}

?>