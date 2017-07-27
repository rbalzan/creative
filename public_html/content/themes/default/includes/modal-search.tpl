<div id="dlg_sresult" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="dlg_data_view_title">{if $titulo}{$titulo|cat:' - <small>Resultados de busqueda</small>'}{else}Resultados de busqueda{/if}</h4>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="col-md-12">	  
				<table id="dt_sresult" class="display data-table compact" cellspacing="0" width="100%">
		    		<thead>
					    <tr>
						{if isset($table.columns) && count($table.columns)}
							{foreach $table.columns as $key => $value}
								<th>{$key}</th>
							{/foreach}
						{/if}					
						<th>{_("Acciones")}</th>
					    </tr>
					</thead>
					<tfoot>
					    <tr>
						{if isset($table.columns) && count($table.columns)}
							{foreach $table.columns as $key => $value}
								<th>{$key}</th>
							{/foreach}
						{/if}	
						<th>{_("Acciones")}</th>
					    </tr>
					</tfoot>
			
					<tbody></tbody>
				</table>
			</div>
		</div>
		
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	$(document).ready(function() {
		
		$('#btn_search').click(searchrecord_handler);
		
		_dt_sresult = $('#dt_sresult').DataTable({
			"language": {
				"info": "Registros <strong>_START_</strong> al <strong>_END_</strong> de un total de <strong>_TOTAL_</strong> registros",
				"infoFiltered": " - filtrado de _MAX_ registros",
				"processing": "Procesando...",
				"search": "Filtrar: ",
				"sEmptyTable":"Sin datos para mostrar...",
				"sLoadingRecords": "Cargando...",
				"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
				"sZeroRecords": "No se encontraron resultados",
				"sSearchPlaceholder": "Filtar resultados...",
				"sDecimal": ",",
				"sInfoThousands":  ",",	
				"searchDelay": 100,	
				"lengthMenu": 
					'Mostrar <select class="form-control" style="display: inline-block;width: auto;">'+
					'<option value="10">10</option>'+
					'<option value="25">25</option>'+
					'<option value="50">50</option>'+
					'<option value="-1">Todos</option>'+
					'</select> páginas',
				"paginate": {
					 "sFirst": '<span class="fa fa-square-o-left"></span>',
					 "sLast": '<span class="fa fa-square-o-right"></span>',
					 "sNext": '<span class="fa fa-caret-right"></span>',
					 "sPrevious": '<span class="fa fa-caret-left"></span>'
				},
				"oAria": {
			        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
			        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
			    }
			}
		});
	});
	
</script>