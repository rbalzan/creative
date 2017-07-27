<script>
var _dt_sresult;

function searchrecord_handler(){
	
	if( $("#search").val() == "" ){
		notify("Debe ingresar un parametro de busqueda", "info");
		$("#search").focus();
		return ;
	}
	
	//$("#btn_search").prop("disabled", true);
	
	var filter = $("#filter").val(),
		value  = $("#search").val();
	
	$.ajax({
		url : "{controller_load}search/" + filter + "/" + value,
	    beforeSend: function( e ) {
			$.isLoading({ text: "Procesando..." });
		},
		type : "GET",
		dataType : "json",		 
		success : function(data) {			
			
			_dt_sresult.clear().draw();
			
			if( data.status == 200 ){
						
				$.each(data.data,function(index, item){
					
					var columns = [];
					$.each(_datable_columns,function(ix, val){
						if( _datable_columns_pk == val){
							columns.push( '<a href="javascript:viewrecord_handler('+item["id"]+')">'+item[val]+"</a>" );
						} else {
							columns.push( item[val] );
						}
					});
					
						
					//Template de Estatus
					columns[columns.length-1] = _template_status
				        .replace("@status_text", item.status_text)
				        .replace("@status_info", item.status_info)
				        .replace("@status_class", item.status_class)
				    ;
				    
				    //Tempalte de Acciones
				    columns.push(
				        _template_action_search
				        	.replace("@id", item.id) //View
				        	.replace("@id", item.id) //Edit
				        	.replace("@id", item.id) //Delete
				    );
				    
			    		
					_dt_sresult.row.add(columns).draw();
					
				});
				$("#dlg_sresult").modal("show");
				
				
			} else if( data.status == 404 ){
				//_token = data.response.token;				
				notify(data.statusText, data.icon);
			}
			
			$("#btn_search").prop("disabled", false);
			$.isLoading("hide");	
	    }
	});	
}
</script>