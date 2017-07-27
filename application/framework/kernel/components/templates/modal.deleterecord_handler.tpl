<script>
/**
* Eliminar un registro* 
* @return
*/
function deleterecord_handler(id){
	bootbox.confirm({
	    title: "<span style=\"text-transform: uppercase;\">{text} - <small>Eliminar registro</small></span>",
	    size: "small",
	    message: "¿Está seguro(a) que quiere eliminar este registro?",
	    buttons: {
	        cancel: {
	            label: '<i class="fa fa-times"></i> Cancelar',
	            className: "btn-danger"
	        },
	        confirm: {
	            label: '<i class="fa fa-check"></i> Confirmar',
	            className: "btn-success"
	        }
	    },
	    callback: function (result) {
	    	if( result ){
	    		deleterecord_callback(id);
	    	}
	    }
	});
}

function deleterecord_callback( id ){
	$.ajax({
		url : "{controller_delete}" + id,
		type : "DELETE",
		dataType: "JSON",
		beforeSend: function( e ) {
			$.isLoading({ text: "Procesando..." });
		}
	}).done(function( data ) {
		$("#tr_" + id).addClass("delete_success");
		if( data.status == 204 ){
			setTimeout(function(){
				_dt_data.row("#tr_"+id).remove().draw( false );
			}, 1100);
		}
		$.isLoading("hide");
	});
}
</script>