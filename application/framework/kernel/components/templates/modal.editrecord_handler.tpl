<script>
/**
* Prepara el formulario para insertar un nuevo registro*
* @param string title Titulo del Modal
* @return
*/
function editrecord_handler( id ){
	
	title = "{text} - <small>Editar registro</small>";
	
	//Inicializar los valores por defecto
	$("#id").val(id);
	
	$("#modal_wiewrecord_submit").show();
	$("#modal_wiewrecord_title").html(title);
	
	clear_handler();
	loaddata_handler( id );
	bloquear_handler( false );
	
	$("#modal_wiewrecord").modal("show");
	
	//edit_mode = true;

	if( typeof editrecord_callback !== "undefined" && jQuery.isFunction( editrecord_callback ) ) {
		editrecord_callback();
	};
}
</script>