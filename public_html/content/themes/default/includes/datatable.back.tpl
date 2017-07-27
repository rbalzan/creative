<style>
.panel-body {
	padding: 15px;
}
.btn-group .btn:not(.dropdown-toggle) {
    border-radius: 0;
    border-radius: 0;
}
table.dataTable thead th,
table.dataTable tfoot th {
    font-weight: bold;
    font-size: 14px;
}
tbody td {
    font-size: 14px;
}


table .btn {
    display: inline-block;
    padding: 2px 8px !important;
    height: 26px;
    margin-bottom: 3px;
    width: 28px;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 0px solid transparent;
    border-radius: 1px;
}

table .btn-default {
	background-color: #d2d2d2;
}

table tbody td {
    padding: 2px 5px !important;
}

.sidebar-menu > li > a,
.sidebar-menu > li > a {
	transition: all 0.5s
}

.sidebar-menu > li:hover > a,
.sidebar-menu > li.active > a {
    color: #ffffff;
    background: #1b1b1b;
    border-left-color: #00b3df;
}
.box {
    position: relative;
    border-radius: 2px;
    background: #ffffff;
    border: 1px solid #d2d6de;
    border-top: 3px solid #d2d6de;
    margin-bottom: 20px;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}

@keyframes save_success_kf {
    0%   { background-color: rgba(89, 198, 247, 0); }
    10%  { background-color: rgba(89, 198, 247, 0.8); }
    20%  { background-color: rgba(89, 198, 247, 0); }
    30%  { background-color: rgba(89, 198, 247, 0.6); }
    40%  { background-color: rgba(89, 198, 247, 0); }
    50%  { background-color: rgba(89, 198, 247, 0.4); }
    60%  { background-color: rgba(89, 198, 247, 0); }
    70%  { background-color: rgba(89, 198, 247, 0.2); }
    80%  { background-color: rgba(89, 198, 247, 0); }
    90%  { background-color: rgba(89, 198, 247, 0.05); }
    100% { background-color: rgba(89, 198, 247, 0); }
}

.save_success{
	animation-name: save_success_kf;
	animation-duration: 5s;
}
.save_success td{
	animation-name: save_success_kf;
	animation-duration: 5s;
}





@keyframes delete_success_kf {
    0%   { background-color: rgba(215, 57, 37, 0); }
    50%  { background-color: rgba(215, 57, 37, 0.3); }
    99%  { background-color: rgba(215, 57, 37, 0);}
    100% { display:none }
}

.delete_success{
	animation-name: delete_success_kf;
	animation-duration: 0.8s;
}
.delete_success td{
	animation-name: delete_success_kf;
	animation-duration: 0.8s;
}






.label {
    display: inline;
    padding: 5px 10px;
    font-size: 80%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 1px;
}


table thead th, 
table thead td {
    padding: 10px 18px;
    border-bottom: 1px solid #b5b5b5 !important;
    color:#333;
}

table tfoot th, 
table tfoot td {
    padding: 10px 18px;
    border-top: 1px solid #b5b5b5 !important;
    color:#333;
}


@media (min-width: 768px){
	.modal-sm {
		width: 330px;
	}
}


.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    color: #333 !important;
    border: 1px solid #b5b5b5 !important;
    background: #f9f9f9 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: white !important;
    border: 1px solid #111 !important;
    background: #353535 !important;
}
</style>

<div ng-controller="data as showCase">
<div class="box box-default">
	{include file="includes/datatable_header.back.tpl"}	
	<div class="box-body">
		<table datatable="" id="dt_data" dt-options="showCase.dtOptions" dt-column-defs="showCase.dtColumnDefs" class="display data-table compact" cellspacing="0" width="100%">
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
	
	        <tbody>
	        
		    {if isset($data) && count($data)}
				{foreach $data as $key => $value}
					{if $value.status >= 0}
					<tr id="tr_{$value.id}">
				        {if isset($table.columns) && count($table.columns)}
							{foreach $table.columns as $ckey => $cvalue}
					        	
					        	<td align="{$cvalue['align']}">
					        		{*Si la columna es de tipo "date"*}
					        		{if $cvalue['type'] == 'date'}	
					        			{$value[$cvalue['field']]|date_format:$cvalue['format']}
					        		
					        		{*Si la columna es de tipo "number"*}
					        		{elseif $cvalue['type'] == 'number'}
					        			{*
					        				'format' => array(
					        					int Dcimanles,
					        					string Separador de deciamles,
					        					string Separador de Miles
					        				)
					        			*}
					        			{number_format($value[$cvalue['field']], $cvalue['format'][0], $cvalue['format'][1], $cvalue['format'][2])}
					        		
					        		{*Si la columna es de tipo "label"*}
					        		{elseif $cvalue['type'] == 'label'}
					        			<span class="label label-{$value[$cvalue['class']]|default:'default'}" {if $cvalue['tooltips']}{html::tooltips($value[$cvalue['tooltips']])}{/if} >{$value[$cvalue['field']]}</span>
					        			
					        		{else}
					        		
					        			{*Si no existe un tipo establecido, pero se tiene un formato*}
					        			{if $cvalue['format']}
						        			{$value[$cvalue['field']]|string_format:$cvalue['format']}
						        		{else}
						        		{*Si no existe un tipo establecido, ni formato*}
						        			{if $cvalue['primary'] == TRUE}
						        				<a href="javascript:viewrecord_handler({$value.id})">{$value[$cvalue['field']]}</a>
						        			{else}
						        				{$value[$cvalue['field']]}
						        			{/if}
					        			{/if}
					        		{/if}
					        		
					        	</td>
					        {/foreach}
						{/if}
						
						<td align="center">
							{if isset($action_datatable_before) && count($action_datatable_before)}
								{foreach $action_datatable_before as $k => $v}
									<button class="btn btn-{$v['color']|default:'default'}" type="button" data-id="{$value.id}" onclick="javascript:{$v['onclick']}" {if $v['tooltip']}{html::tooltips("{$v['tooltip']}")}{/if}><span class="fa fa-{$v['icon']|default:'circle'}"></span></button>
								{/foreach}
							{/if}
							
							{if $table.view}<button class="btn btn-default" onclick="javascript:viewrecord_handler({$value.id})" title="" type="button" {html::tooltips("Visualizar los detalles de este registro")}><span class="fa fa-eye"></span> </button>{/if}
							{if $table.edit}<button class="btn btn-info" onclick="javascript:editrecord_handler({$value.id})" title="" type="button"  {html::tooltips("Haga click para editar este registro")}><span class="fa fa-edit"></span> </button>{/if}
							{if $table.delete}<button class="btn btn-danger" onclick="javascript:deleterecord_handler({$value.id})" title="" type="button"  {html::tooltips("Haga click para eliminar este registro")}><span class="fa fa-trash"></span> </button>{/if}
							
							{if isset($action_datatable_after) && count($action_datatable_after)}
								{foreach $action_datatable_before as $k => $v}
									<button class="btn btn-{$v['color']|default:'default'}" type="button" data-id="{$value.id}" onclick="javascript:{$v['onclick']}" {if $v['tooltip']}{html::tooltips("{$v['tooltip']}")}{/if}><span class="fa fa-{$v['icon']|default:'circle'}"></span></button>
								{/foreach}
							{/if}
						</td>
					</tr>
					{/if}
				{/foreach}
			{/if}			
	        </tbody>
		</table>		
		{if $paginator}{$paginator}{/if}
	</div><!-- /.box-body -->
</div><!-- /.box -->
</div>
<script>
	var _dt_data;
	$(document).ready(function() {
		//aoColumns: [ { sWidth: "45%" }, { sWidth: "45%" }, { sWidth: "10%", bSearchable: false, bSortable: false } ]
		_dt_data = $('#dt_data').DataTable({
	{if $dt_notsearching==true}"searching": false,{/if}
	{if $dt_notpaginate==true}"paginate": false,"info":false,{/if}
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