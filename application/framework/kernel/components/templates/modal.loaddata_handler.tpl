<script>

/**
* Traer los datos mediante Ajax
*/
function loaddata_handler( id ){
	
	$.ajax({
		url : "{controller_load}find/" + id,
		data : {
			id 		: id,
			token 	: _token,
	    },
	    beforeSend: function( e ) {
			$.isLoading({ text: "Procesando..." });
		},
		type : "GET",		 
		dataType : "json",		 
		success : function(data) {
			
			$.isLoading( "hide" );			
	    	if( data.status == 200 ){
	    		$.each(data.data, function( index, item ){
	    			
	    			//Si es un Select se le agrega el target
	    			if( $("#"+index).is("select") ){
	    				
	    				if( is_array(item) ){
	    					var _items = [];
	    					//Parsear los datos para obtener un array con los ID
	    					$.each(item, function( index_item, item_item ){
								_items.push(item_item["id"]);
							});
							$("#"+index).val(_items).change();
						} else {
							if( new String(item).contains(",") ){
								var it = item.to_array(",");
								$("#"+index).val(it).change();
							} else {
								$("#"+index).val(item).change();
							}
						}
						
	    			} else {
	    				$("#"+index).val(item);
	    			}
	    		});
				//_token = data.response.token;				
			} else {					
				notify(data.statusText, data.icon);
			}				
	    }
	});
}
</script>