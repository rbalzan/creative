<script>
 
/**
* Prepara el formulario para insertar un nuevo registro*
* @param string title Titulo del Modal
* @return
*/
function addrecord_handler( title ){
	
	title = Object.is_string(title) ? title : "{text} - <small>Agregar nuevo</small>";
	
	//Inicializar los valores por defecto
	$("#id").val(-1);
	
	$("#modal_wiewrecord_submit").show();
	$("#modal_wiewrecord_title").html(title);
	
	bloquear_handler( false );
	clear_handler();
	
	$("#modal_wiewrecord").modal("show");
	
	//edit_mode = true;
	 

	if( typeof addrecord_callback !== "undefined" && jQuery.isFunction( addrecord_callback ) ) {
		addrecord_callback();
	};
}
</script>