<script>
	/**
	* Visualizar la informacion de un registro
	* 
	* @return
	*/
	function viewrecord_handler(id){
		edit_mode = false;
		
		clear_handler();
		
		
		$("#id").val(id);
		loaddata_handler( id );
		bloquear_handler( true );
		
		$("#modal_wiewrecord_submit").hide();
		
		$("#modal_wiewrecord_title").html("{text} - <small>Visualizar</small>");
		$("#modal_wiewrecord").modal("show");
	}
</script>