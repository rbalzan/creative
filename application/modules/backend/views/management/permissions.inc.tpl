<table id="table_permisos" class="table display table-fixed" cellspacing="0" width="100%">
	<thead>
	    <tr>
	    	<th>Módulo</th>
	    	<th class="text-center"><span class="fa fa-eye" {add_tooltips('Lectura')}></span> <span class="hidden-xs">Lectura</span></th>
	    	<th class="text-center"><span class="fa fa-plus" {add_tooltips('Agregar nuevos registros')}></span> <span class="hidden-xs">Agregar</span></th>
	    	<th class="text-center"><span class="fa fa-edit" {add_tooltips('Editar registros')}></span> <span class="hidden-xs">Editar</span></th>
	    	<th class="text-center"><span class="fa fa-trash" {add_tooltips('Eliminar registros')}></span> <span class="hidden-xs">Eliminar</span></th>
	    	<th class="text-center"><span class="fa fa-print" {add_tooltips('Imprimir registros')}></span> <span class="hidden-xs">Imprimir</span></th>
	    	<th class="text-center"><span class="fa fa-window-maximize" {add_tooltips('Personalizar opciones. Permite configurar el acceso a ciertos campos')}></span> <span class="hidden-xs">Personalizar</span></th>
	    </tr>
	</thead>
	<tfoot>
	     <tr>
	    	<th>Módulo</th>
	    	<th class="text-center"><span class="fa fa-eye"></span> <span class="hidden-xs">Lectura</span></th>
	    	<th class="text-center"><span class="fa fa-plus"></span> <span class="hidden-xs">Agregar</span></th>
	    	<th class="text-center"><span class="fa fa-edit"></span> <span class="hidden-xs">Editar</span></th>
	    	<th class="text-center"><span class="fa fa-trash"></span> <span class="hidden-xs">Eliminar</span></th>
	    	<th class="text-center"><span class="fa fa-print"></span> <span class="hidden-xs">Imprimir</span></th>
	   		<th class="text-center"><span class="fa fa-window-maximize"></span> <span class="hidden-xs">Personalizar</span></th>
	    </tr>
	</tfoot>
    <tbody>
    
{if isset($menus.menus) && count($menus.menus)}
	{foreach $menus.menus as $key => $value}
		
		
    	<tr id="tr_{$value.table}">
            <td><label for="read-{$value.table}">{$value.title}</label></td>
            <!--Lectura-->
            <td align="center">
				<input id="read-{$value.table}" type="checkbox" class="check read permission {$value.table}" data-module="{$value.table}" data-permission="read">
				<label class="check" for="read-{$value.table}"></label>
			</td>

            <!--Agregar-->
            <td align="center">
            	<div {if $key ==0}style="display: none"{/if}>
            		<input id="created-{$value.table}" type="checkbox" class="check permission {$value.table}" data-module="{$value.table}" data-permission="created">
					<label class="check" for="created-{$value.table}"></label>
				</div>
            </td>
            
            <!--Editar-->
            <td align="center">
            	<div {if $key ==0}style="display: none"{/if}>
                	<input id="update-{$value.table}" type="checkbox" class="check permission {$value.table}" data-module="{$value.table}" data-permission="update">
					<label class="check" for="update-{$value.table}"></label>
				</div>
            </td>
            
            <!--Eliminar-->
            <td align="center">
            	<div {if $key ==0}style="display: none"{/if}>
                	<input id="delete-{$value.table}" type="checkbox" class="check permission {$value.table}" data-module="{$value.table}" data-permission="delete">
					<label class="check" for="delete-{$value.table}"></label>
				</div>
            </td>
            
            <!--Imprimir-->
            <td align="center">
            	<div {if $key ==0}style="display: none"{/if}>
                	<input id="print-{$value.table}" type="checkbox" class="check permission {$value.table}" data-module="{$value.table}" data-permission="print">
					<label class="check" for="print-{$value.table}"></label>
				</div>
            </td>
            
            <!--Personalizar-->
            <td align="center">
            	<div {if $key ==0}style="display: none"{/if}>
                	<button class="customize btn btn-info" onclick="javascript:customize('{$value.table}')" data-module="{$value.table}" type="button"><span class="fa fa-edit" ></span></button>
				</div>
            </td>
        </tr>
        
        {/foreach}
	{/if}
    </tbody>
</table>



<script>


$('.permission').change(function(e){
	var base = $(e.target);
	var data = base.data('module');
	var value = base.prop('checked');
	if( value == true  && $('.read.'+data).prop('checked') == false ) $('.read.'+data).prop('checked',value);
});

$('.read').change(function(e){
	var base = $(e.target);
	var data = base.data('module');
	var value = base.prop('checked');
	
	var default_module = $('#default_module').val();
	
	if( data == default_module ){
		$('.'+data).prop('checked',true);
		return;
	}
	
	if( value == false ){
		$('.'+data).prop('checked',value);
	}
	
});

$('#default_module').change(function(){
	var base = $(this);
	$('#read-'+base.val()).prop('checked',true)
})

	
</script>

