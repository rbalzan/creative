<?php

class HtmlBasic extends ComponentBase implements IComponent {
	

	
	public function initialize( $property = array() ){
		$this->class = __CLASS__;
		
		$default_parent = $property['default_parent'] ? $property['default_parent'] : 'body';
		$property['id'] = $default_parent;
		$this->default_parent = $default_parent;
		
		$attr = $this->default_attr($property);
		$attr->iscontainer = TRUE;
		
		$this->_components[$this->default_parent] = array( 'attr' => $attr, 'html'=> '{*content*}');
		return $this;
	}
	
	
	/**
	* 
	* @param undefined $property
	* 
	* @return
	*/
	public function panelbox( $property = array() ){
		
		$attr = $this->default_attr($property);
		$attr->iscontainer = TRUE;
		$attr->idname = 'panelbox';
		
		$panel = $this->get_template( 'panelbox' );
		
		foreach( $attr as $key => $value ){
			$panel = str_ireplace( '{*'.$key.'*}' , $value ,$panel);
		}
		
		$this->_components[$attr->id] = array( 'attr' => $attr, 'html'=> $panel);
		return $this;
	}
	
	
	
	/**
	* Crea un nuevo Input
	* @param undefined $property
	* 
	* @return
	*/
	public function input( $property = array() ){
		
		$attr = $this->default_attr($property);	
		$attr->idname = 'input';
			
		$input = $this->get_template( 'input' );
		
		foreach( $attr as $key => $value ){
			$input = str_ireplace( '{*'.$key.'*}' , $value ,$input);
		}
		
		$this->_components[$attr->id] = array( 'attr' => $attr, 'html'=> $input);
		
		return $this;
	}
	

	/**
	* Crea un nuevo Input
	* @param undefined $property
	* 
	* @return
	*/
	public function button( $property = array() ){
		
		$attr = $this->default_attr($property);	
		$attr->idname = __FUNCTION__;
			
		$input = $this->get_template( __FUNCTION__ );
		
		foreach( $attr as $key => $value ){
			$input = str_ireplace( '{*'.$key.'*}' , $value ,$input);
		}
		
		$this->_components[$attr->id] = array( 'attr' => $attr, 'html'=> $input);
		
		return $this;
	}


	/**
	* Crea un nuevo Input
	* @param undefined $property
	* 
	* @return
	*/
	public function form( $property = array() ){
		
		$attr = $this->default_attr($property);	
		$attr->idname = __FUNCTION__;
		$attr->iscontainer = TRUE;
			
		$component = $this->get_template( __FUNCTION__ );
		
		foreach( $attr as $key => $value ){
			$component = str_ireplace( '{*'.$key.'*}' , $value ,$component);
		}
		
		$this->_components[$attr->id] = array( 'attr' => $attr, 'html'=> $component);
		
		return $this;
	}
	
	
	/**
	* Crea Image Upload
	* @param undefined $property
	* 
	* @return
	*/
	public function image_upload( $property = array() ){
		
		$attr = $this->default_attr($property);	
		$attr->idname = 'imageupload';
			
		$input = $this->get_template( 'imageupload' );
		
		foreach( $attr as $key => $value ){
			$input = str_ireplace( '{*'.$key.'*}' , $value ,$input);
		}
		
		$this->_components[$attr->id] = array( 'attr' => $attr, 'html'=> $input);
		
		return $this;
	}
	
	/**
	* 
	* @param undefined $text
	* @param undefined $title
	* @param undefined $placement
	* @param undefined $content
	* 
	* @return
	*/
	public function popover($text, $title = '', $placement = 'top', $content='body'){
    	$text 		= $text ? $text : '';
    	$title		= $title ? $title : '';
    	
		$template = 'data-container="'.$content.'" '.
					'data-toggle="popover" '.
					'data-html="true" '.
					'data-trigger="hover" '.
					($title ? 'title="'.$title.'" ' : '').
					'data-placement="'.$placement.'" '.
					'data-content="'.$text.'"';
		return $template;
	}
	
	
	/**
	* paraledepipedo
	* 
	* @return
	*/
	public function column( $property = array() ){
		$attr = $this->default_attr($property);
		$attr->iscontainer = TRUE;
		$attr->idname = 'column';
		$component = '<div class="{*col*}">
	{*content_column*}
</div>';
		
		foreach( $attr as $key => $value ){
			$component = str_ireplace( '{*'.$key.'*}' , $value ,$component);
		}
		
		$this->_components[$attr->id] = array( 'attr' => $attr, 'html'=> $component);
		
		return $this;
	}
	
	/**
	* paraledepipedo
	* 
	* @return
	*/
	public function row(){
		return '		<div class="row">
		{*content_row*}
		</div>';
	}	
	
	
	/**
	* 
	* @param undefined $property
	* 
	* @return
	*/
	public function write( $property = array() ){
		
		$html = '';
		$parents = array();
		$childs = array();
				
		
		//recorrer cada componente
		foreach( $this->_components as $id => $component){
			if ( $component['attr']->iscontainer === TRUE ){
				$parents[$component['attr']->id] = $component;
			} else {
				$childs[$component['attr']->id] = $component;
			}
		}
		foreach( $childs as $id => $component){
			
			$parent_id = $component['attr']->parent;
			
			if( $parent_id != NULL ){
				
				$parent 		= $this->_components[$parent_id];
				$parent_attr	= $parent['attr'];
				$parent_html	= $parent['html'];
				$parent_content	= '{*content_'.$parent_attr->idname.'*}';
				
				$parent_html = str_ireplace( $parent_content, $component['html'] . $parent_content, $parent_html);
				$this->_components[$parent_id]['html'] = $parent_html;
				unset($this->_components[$id]);
			}
			
		}
		
		
		foreach( $this->_components as $id => $component){
			
			$parent_id = $component['attr']->parent;
			
			if( $parent_id != NULL ){
				
				$parent 		= $this->_components[$parent_id];
				$parent_attr	= $parent['attr'];
				$parent_html	= $parent['html'];
				$parent_content	= '{*content_'.$parent_attr->idname.'*}';
				
				$parent_html = str_ireplace( $parent_content, $component['html'] . $parent_content, $parent_html);
				$this->_components[$parent_id]['html'] = $parent_html;
				unset($this->_components[$id]);
			}
			
		}
		

		
		foreach( $this->_components as $parent_id => $component){
			$html .= $component['html'];
		}
		
		
		$GLOBALS['CREATIVE']['echo'][] = $html;
		return $this;
		$html .= $this->get_html_container($component);
	}
	
	
	/**
	* 
	* @param undefined $component
	* 
	* @return
	*/
	public function get_html_container( $component ){
		
		
		/*$html = $component['html'];		
		$comp = '';
		
		if( $component['attr']->container !== NULL ){
			
			foreach( $component['attr']->container as $child_id => $child){
				
				if ( $this->_components[$child]['attr']->iscontainer === TRUE ){
					$comp .= $this->get_html_container($this->_components[$child]);
					unset($this->_components[$child]);
				}
				
				$comp .= $this->_components[$child]['html'];
				$html = str_ireplace( '{*content*}', $comp, $html);
			}
		} else {
			
		}
		
		
		return $html;*/
	}
	

	
	
}
?>