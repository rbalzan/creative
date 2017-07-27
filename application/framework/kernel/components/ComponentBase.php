<?php


abstract class ComponentBase {
	
	protected 
		$components = array(),
		$containers = array(),
		$class,
		$default_parent = '';
		
	protected function get_template( $template ){
		$template = PATH_APP . 'components' .DS. 'templates' .DS. strtolower($this->class) .'.'. $template . '.tpl';
		if( ! file_exists( $template  ) ){
            return false;
        }
        return file_get_contents( $template );
	}
	
	protected function tooltip_required(){
		return '<span class="fa fa-circle" style="font-size: 6px; color: #ce0000"  data-toggle="tooltip" data-placement="top" title="Este campo es requerido"></span>';
	}
	
	protected function tooltip_info( $title, $position = 'top' ){
		$tooltips = '<span class="fa fa-info-circle" data-toggle="tooltip" data-placement="'.$position.'" title="'.$title.'"></span>';
		return $tooltips;
	}
	
	
	/**
	* 
	* @param undefined $attrs
	* 
	* @return
	*/
	protected function default_attr( $attrs ){
		
		$id 	= substr(md5(time()), 0, 10);
		
		$label 	= $attrs['label'] 		? $attrs['label'] 	: $id;
		$id 	= $attrs['id'] 			? $attrs['id'] 		: $id;
		$_col	= isset($attrs['col']) 	? $attrs['col'] 	: array('md'=>12);
		
		
		if( $_col ){
			$col = '';
			foreach($_col as $key => $value){
				$col .= 'col-' . $key .'-'. $value .' ';
			}
		}
		
		return (object) array(
			'idname' 		=> '',
            'col' 			=> $col,
            'label'	 		=> $label,
            'id' 			=> $id,
            'type' 			=> $attrs['type'] 			? $attrs['type'] 			: 'text',
            'guid'			=> $attrs['guid'] 			? $attrs['guid'] 			: NULL,
            'items' 		=> $attrs['type']			? $attrs['items'] 			: NULL,
            'default' 		=> $attrs['default']		? $attrs['default'] 		: NULL,
            'multiple'		=> $attrs['multiple']==TRUE ? TRUE 						: FALSE,
            'readonly' 		=> $attrs['readonly']==TRUE ? 'readonly' 				: '',
            'required_info'	=> $attrs['required']==TRUE ? $this->tooltip_required() : '',
            'required' 		=> $attrs['required']==TRUE ? 'required' 				: '',
            'class'			=> $attrs['class'] 			? $attrs['class'] 			: '',
            
            'autocomplete' 	=> $attrs['autocomplete']==TRUE ? 'autocomplete' 		: '', 
                       
            'min' 			=> $attrs['min'] 			? 'min="'.$attrs['min'].'"' : '', 
            'max' 			=> $attrs['max'] 			? 'max="'.$attrs['max'].'"' : '',
                        
            'path'			=> $attrs['path'] 		 	? $attrs['path'] 			: NULL,
            'source'		=> $attrs['source'] 		? $attrs['source'] 			: NULL,
            'binding' 		=> $attrs['binding'] 		? $attrs['binding'] 		: NULL,
            
            'tooltip' 		=> $attrs['tooltip']		? $this->tooltip_info($attrs['tooltip'])		: '',
            'maxlength' 	=> $attrs['maxlength'] 		? 'maxlength="'.$attrs['maxlength'].'"' 		: '', 
            
            'value' 		=> $attrs['value'] 			? 'value="'.$attrs['value'].'"' 				: '', 
            'title' 		=> $attrs['title'] 			? 'title="'.$attrs['title'].'"' 				: '', 
            'placeholder' 	=> $attrs['placeholder'] 	? 'placeholder="'.$attrs['placeholder'].'"' 	: '', 
            'parent' 		=> $attrs['parent']			? $attrs['parent']								: NULL,
            'iscontainer' 	=> FALSE,
            'childs' 		=> $attrs['childs'] 		? $attrs['childs'] 		: NULL, 
           
        );
	}
	
	
	
}

?>