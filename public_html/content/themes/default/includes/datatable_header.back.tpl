<div class="box-header with-border">
	<h4 class="box-title">{$title|unescape}</h4>
</div>
<div class="row" style="margin-bottom: 10px">
	<div class="col-md-12">
		<div class="btn-group" role="group" aria-label="">
			{if $btn_add}<button id="btn_add" type="button" class="btn btn-default" {html::tooltips("Agregar un nuevo registro")}><span class="fa fa-plus"></span>{if $btn_add_text} Agregar{/if}</button>{/if}
		   	{if $btn_print}<button id="btn_print" onclick="javascript:print_page()" type="button" class="btn btn-default" {html::tooltips("Imprimir el listado")}><span class="fa fa-print"></span></button>{/if}
		   	{if $btn_shared}<button id="btn_shared" type="button" class="btn btn-default" {html::tooltips("Compartir el listado")}><span class="fa fa-share-alt"></span></button>{/if}
		   	
		   	<!--Barra de Busqueda-->
		   	<div class="input-group">
		   	
		   	{if $search}
				<div class="input-group-btn" style="width: 25%" {html::tooltips("Filtros de busqueda")}>
					<select id="filter" class="form-control select2" style="width: 100%">
					{if isset($filters) && count($filters)}
						{foreach $filters as $key => $value}
						<option value="{$value}" {if $value=='all'}selected{/if}>{$key}</option>
						{/foreach}
					{/if}
					</select>
				</div>
				<input id="search" type="text" class="form-control" placeholder="Ingrese su busqueda aquí..." maxlength="50">
				<span class="input-group-btn" {html::tooltips("Buscar...")}>
					<button id="btn_search" class="btn btn-default" type="button"><span class="fa fa-search"></span></button>
				</span>
			{/if}
				
		    {if $btn_search_avanced}
		    	<span class="input-group-btn" data-toggle="modal" data-target="#dlg-busqueda-avanzada" {html::tooltips("Busqueda avanzada")}>
					<button id="buscar-btn" class="btn btn-default" type="button"><span class="fa fa-filter"></span></button>
				</span>
			{/if}
			</div>
		</div>
	</div>
</div>