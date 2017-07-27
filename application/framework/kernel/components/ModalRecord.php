<?php



class ModalRecord {
	
	const SIZE_FULL 	= 'full';
	const SIZE_LARGE 	= 'lg';
	const SIZE_MEDIUM 	= 'md';
	const SIZE_SMALL 	= 'sm';

	/**
	* @col Distribución de las columnas
	* @label texto Inidcativo del control
	* @id ID del Control
	* @type Indica el tipo de campo
	* @required Indica si es requerido
	*/
 	const INPUT = '
	 	<div class="{col}" style="margin-bottom:5px">
			<label for="{id}">{label} {required_info} </label>
			<input id="{id}" type="{type}" value="" class="form-control {required}" {required} {readonly} {guid}>
		</div>';



 	const SELECT = '
	 	<div class="{col}"  style="margin-bottom:5px">
			<label for="{id}">{label} {required_info}</label>
			<div class="fancy-form fancy-form-select">
				<select id="{id}" class="form-control {required} select2" {required} {multiple} style="width:100%">
					{option}
				</select>
			</div>
		</div>';
		
		
		
	const FORM_GROUP = '<div class="form-group">{content}</div>';
	
	
	private $_attrs;
 	private $_fields;
 	private $_header;
 	private $_modal;
 	private $_data_fields = '';
 	private $_controller;
 	private $_text;
 	private $_property;
 	
 	function __construct() {}
	
	/**
	* 
	* @param undefined $property
	* 
	* @return
	*/
	public function initialize( $property = array() ){
		
		$this->_tpl_modal 	= $this->get_template( 'modal' );
 		$this->_tpl_header	= $this->get_template( 'modal.header' );
 		
 		$this->_tpl_input	= $this->get_template( 'input' );
 		$this->_tpl_select	= $this->get_template( 'select' );
 		
 		$this->_tpl_addrecord_handler		= $this->get_template( 'modal.addrecord_handler' );
 		$this->_tpl_asaverecord_handler		= $this->get_template( 'modal.saverecord_handler' );
 		$this->_tpl_editrecord_handler		= $this->get_template( 'modal.editrecord_handler' );
 		$this->_tpl_viewrecord_handler		= $this->get_template( 'modal.viewrecord_handler' );
 		$this->_tpl_deleterecord_handler	= $this->get_template( 'modal.deleterecord_handler' );
 		$this->_tpl_loaddata_handler		= $this->get_template( 'modal.loaddata_handler' );
 		$this->_tpl_searchrecord_handler	= $this->get_template( 'modal.searchrecord_handler' );
 		
 		
 		
		$this->_attrs = [];
		$this->_property = $property;
		
		//Colocar Texto del Header
		$this->_text = $property['text'] ? $property['text'] : '';	
 		$this->_tpl_header = str_ireplace('{text}', $this->_text, $this->_tpl_header);
 		
 		//Colocar el Tamaño del modal
 		$this->_size = $property['size'] ? $property['size'] : 'md';
 		$this->_tpl_modal = str_ireplace('{size}', $this->_size, $this->_tpl_modal);
 		
 		//Agregar Header
		$this->_tpl_modal = str_ireplace('{header}', $this->_tpl_header, $this->_tpl_modal);
		
		return $this;
	}
	
	
	/**
	* Crea un template de los atributos necesario para el componente Modal
	* @param undefined $attrs
	* 
	* @return
	*/
	private function attrs_default( $attrs ){
		$id = substr(md5(time()), 0, 6);
		return (object) array(
            'col' 		=> isset($attrs['col'])			? $attrs['col'] 		: array('md'=>12),
            'label'	 	=> isset($attrs['label']) 		? $attrs['label'] 		: $id,
            'id' 		=> isset($attrs['id']) 			? $attrs['id'] 			: $id,
            'type' 		=> isset($attrs['type']) 		? $attrs['type'] 		: 'text',
            'required' 	=> isset($attrs['required']) 	? $attrs['required'] 	: '',
            'guid'		=> isset($attrs['guid'])		? $attrs['guid'] 		: NULL,
            'readonly' 	=> isset($attrs['readonly']) 	? $attrs['readonly'] 	: '',
            'items' 	=> isset($attrs['type']) AND isset($attrs['items'])		? $attrs['items'] 		: '',
            'default' 	=> isset($attrs['default'])		? $attrs['default'] 	: NULL,
            'multiple'	=> isset($attrs['multiple']) 	? TRUE 					: FALSE,
            'path'		=> isset($attrs['path']) 		? $attrs['path'] 		: NULL,
            'source'	=> isset($attrs['source']) 		? $attrs['source'] 		: NULL,
            'datasource'=> isset($attrs['datasource']) ? $attrs['datasource'] : NULL,
        );
	}
	
	
	
	/**
	* Obtiene el contenido de una template de un componente
	* @param undefined $template Nombre del Template
	* 
	* @return
	*/
	private function get_template( $template ){
		if( ! file_exists( PATH_KERNEL . 'components' .DS. 'templates' .DS. $template . '.tpl' ) ){
            return false;
        }
        return file_get_contents(  PATH_KERNEL . 'components' .DS. 'templates' .DS. $template . '.tpl' );
	}
	
	
	/**
	* Agrega un nuevo campo de texto
	* @param undefined $attr
	* 
	* @return
	*/
 	public function add_field( $attr ){
 		
 		$attr = $this->attrs_default($attr);
 		 
 		switch( TRUE ){
		 	case $attr->type === 'text' or $attr->type === 'email' or $attr->type === 'tel':
		 		$field = self::INPUT;
		 	break;
		 	
		 	case $attr->type === 'hidden':
		 		$field = '<input id="{id}" type="hidden" value="">';
		 	break;
		 	
		 	case $attr->type === 'date':
		 		$field = self::INPUT;
		 	break;
		 	
		 	case $attr->type == 'number':
		 		$field = self::INPUT;
		 		$field = str_ireplace('class="','class="numeric ',$field);
		 		$field = str_ireplace('input','input style="text-align:right"',$field);
		 	break;
		 	
		 	case $attr->type == 'select':
		 	
		 		$field = self::SELECT;
		 		$option = '';
		 		
		 		//Si hay DataSources
		 		if( isset($attr->datasource) ){
					
					/*$model		= $attr->datasource['source'];
					$key		= $attr->datasource['key'];
					$val		= $attr->datasource['field'];	*/
					$ds = $attr->datasource;
					$data = $ds->get_data();			
					$binding	= array();
					
					/*if( $attr->multiple == FALSE )
						$option .= '<option selected default value="-1" deisable>Seleccione</option>';
					*/
					foreach( $data as $key => $value){
						$option .= '<option value="' .$key. '">' .$value. '</option>';
					}
					$field = str_ireplace('{option}',$option, $field);
					
				} else {
					if( is_array($attr->items) ){
						foreach($attr->items as $key => $value){
							if( $attr->default == $key )
								$option .= '<option selected default value="' .$key. '">' .$value. '</option>';
							else 
								$option .= '<option {if $key==-1}selected default{/if} value="' .$key. '">' .$value. '</option>';
						}
				 		$field = str_ireplace('{option}',$option, $field);
					} 
				}
				
		 	break;
		 	
		 	case $attr->type == 'include':
		 		/*$field = '{include file="'.$attr->path.'"}';
		 		$this->_fields[] = $field;
		 		return $this;*/
		 	break;
		 	
		 	case $attr->type == 'source':
		 	
			 	foreach($attr->col as $key => $value){
					$col .= 'col-' . $key .'-'. $value .' ';
				}
		 		$div = str_ireplace('{col}',$col ,'<div class="{col}"  style="margin-bottom:5px">');
		 		$this->_fields[] = $div . $attr->source.'</div>';
		 		return $this;
		 	break;
		 	
		 	
		 	default:
		 		$field = self::INPUT;
		 	break;
		}
 		
 			
 		$col = '';
 		
 		//Formate las columnas
 		foreach($attr->col as $key => $value){
			$col .= 'col-' . $key .'-'. $value .' ';
		}
		
		//Determina si el campo es requerido
		if( $attr->required ){
			$required = 'required';
			$required_info = '<span class="fa fa-circle" style="font-size: 6px; color: #ce0000"  data-toggle="tooltip" data-placement="top" title="Este campo es requerido"></span>';
		} else {
			$required = '';
			$required_info = '';
		}
		
		
		//Determina si el campo es requerido
		if( $attr->guid ){
			$guid = 'value="' . substr(md5(time()-100).md5(time()+100), 0, $attr->guid) . '" text-guid';
		} else {
			$guid = '';
		}
		
		
		//Determina si el campo es requerido
		if( $attr->readonly ){
			$readonly = 'readonly';
		} else {
			$readonly = '';
		}
		
		if( $attr->multiple ){
			$multiple = 'multiple="multiple"';
		} else {
			$multiple = '';
		}
		
		
		
 		$field = str_ireplace('{col}'			,$col			,$field);
 		$field = str_ireplace('{id}'			,$attr->id		,$field);
 		$field = str_ireplace('{label}'			,$attr->label	,$field);
 		$field = str_ireplace('{type}'			,$attr->type	,$field);
 		$field = str_ireplace('{required}'		,$required		,$field);
 		$field = str_ireplace('{required_info}'	,$required_info	,$field);
 		$field = str_ireplace('{readonly}'		,$readonly		,$field);
 		$field = str_ireplace('{guid}'			,$guid			,$field); 	
 		$field = str_ireplace('{multiple}'		,$multiple		,$field); 
 		
 			
 		$field = trim($field);
 		
 		$this->_data_fields .= $attr->id . ' : $("#'.$attr->id.'").val(), ';
 		$this->_fields[] = $field;
 		$this->_attrs[] = $attr;
 		
 		return $this;
	}
	
	
	/**
	* Imprime el componente
	* 
	* @return
	*/
	public function write( $property = array()){
		
		$modal_body = '';
		if( count($this->_fields)>0 )
		foreach( $this->_fields as $key => $value){
			$modal_body .= $value;
		}
		
		//---------------------
		
		
		//Agregar Body al modal
		$this->_tpl_modal = str_ireplace('{body}', $modal_body, $this->_tpl_modal);		
		######################################################
		$GLOBALS['CREATIVE']['echo'][] = $this->_tpl_modal;
		
		//---------------------
		
		//SCRIPT Save Record
		$script_save_record = $this->_tpl_asaverecord_handler;
		$this->_data_fields = substr($this->_data_fields, 0, strlen($this->_data_fields)-2);
		$script_save_record = str_ireplace('{data_fields}', $this->_data_fields, $script_save_record);
		$script_save_record = str_ireplace('{controller_save}', $this->_property['controller_save'], $script_save_record);
		$script_save_record = str_ireplace('{text}', $this->_text, $script_save_record);
		
		$script_add_record = $this->_tpl_addrecord_handler;
		$script_add_record = str_ireplace('{text}', $this->_text, $script_add_record);
		######################################################
		$GLOBALS['CREATIVE']['echo'][] = $script_add_record;
		
		//---------------------
		
		//script de carga de datos por AJAX
		$script_loaddata = str_ireplace('{controller_load}', $this->_property['controller_load'], $this->_tpl_loaddata_handler);
		######################################################
		if( $this->_property['controller_load'] )
			$GLOBALS['CREATIVE']['echo'][] = $script_loaddata;
		
		
		
		//---------------------
		
		//Eliminar información
		$script_delete_record = str_ireplace('{text}', $this->_text, $this->_tpl_deleterecord_handler);
		$script_delete_record = str_ireplace('{controller_delete}', $this->_property['controller_delete'], $script_delete_record);
		######################################################
		$GLOBALS['CREATIVE']['echo'][] = $script_delete_record;
		
		// --------------------
				
		//Search
		$script_search = str_ireplace('{controller_load}', $this->_property['controller_load'], $this->_tpl_searchrecord_handler);
		$script_search = str_ireplace('{text}', $this->_property['text'], $script_search);
		######################################################
		if( $this->_property['controller_load'] )
			$GLOBALS['CREATIVE']['echo'][] = str_ireplace('{controller_load}', $this->_property['controller_load'], $script_search);
		
		
		// --------------------
		
		
		//View Record
		$GLOBALS['CREATIVE']['echo'][] = str_ireplace('{text}', $this->_text, $this->_tpl_viewrecord_handler);
		
		
		//Guardar		
		if( $this->_property['controller_save'] )
			$GLOBALS['CREATIVE']['echo'][] = $script_save_record;
		
		
		//Editar
		$GLOBALS['CREATIVE']['echo'][] = str_ireplace('{text}', $this->_text, $this->_tpl_editrecord_handler);
				
		
		
		return $this;
	}
}

?>