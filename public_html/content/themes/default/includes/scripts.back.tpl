{if $general  == TRUE}
<script>

	var _module = '{$module}';
	var _token = '{$token}';

	var _template_action = '<div style="text-align:center">{if $table.view}<button class="btn btn-default" onclick="javascript:viewrecord_handler(@id)" title="" type="button" data-placement="top" data-toggle="tooltip" toggle="tooltip" data-original-title="Visualizar los detalles de este registro "><span class="fa fa-eye"></span> </button>{/if} ' +
			' {if $table.edit}<button class="btn btn-info" onclick="javascript:editrecord_handler(@id)" title="" type="button" data-placement="top" data-toggle="tooltip" toggle="tooltip" data-original-title="Haga click para editar este registro"><span class="fa fa-edit"></span> </button>{/if} ' +
			' {if $table.delete}<button class="btn btn-danger" onclick="javascript:deleterecord_handler(@id)" title="" type="button" data-placement="top" data-toggle="tooltip" toggle="tooltip" data-original-title="Haga click para eliminar este registro"><span class="fa fa-trash"></span> </button>{/if} ' +
			'</div>';
	
	var _template_action_search = '<div style="text-align:center">{if $table.view}<button class="btn btn-default" onclick="javascript:viewrecord_handler(@id);" title="" type="button" data-placement="top" data-toggle="tooltip" toggle="tooltip" data-original-title="Visualizar los detalles de este registro "><span class="fa fa-eye"></span> </button>{/if} ' +
			' {if $table.edit}<button class="btn btn-info" onclick="javascript:editrecord_handler(@id)" title="" type="button" data-placement="top" data-toggle="tooltip" toggle="tooltip" data-original-title="Haga click para editar este registro"><span class="fa fa-edit"></span> </button>{/if} ' +
			'</div>';
			
			
	var _template_status = '<div style="text-align:center"><span class="label label-@status_class" {html::tooltips("@status_info")}>@status_text</span></div>';
	
	var _datable_columns = [];
	var _datable_columns_pk = '';
	
	{if isset($table.columns) && count($table.columns)}
		{foreach $table.columns as $key => $value}
			_datable_columns.push('{$value["field"]}');
			{if $value["primary"] == true}_datable_columns_pk = '{$value["field"]}';{/if}
		{/foreach}
	{/if}
				
	/**
	* Bloquear o desbloquear controles
	**/
	function bloquear_handler( bloquear_control ){
		if( bloquear_control ){
			$('#modal_wiewrecord .form-control').prop('disabled', true);
			$('#modal_wiewrecord .check').prop('disabled', true);
			$('#modal_wiewrecord .form-view button').prop('disabled', true);
		} else {
			$('#modal_wiewrecord .form-control').prop('disabled', false);
			$('#modal_wiewrecord .check').prop('disabled', false);
			$('#modal_wiewrecord .form-view button').prop('disabled', false);
		}
	}
	
	function clear_handler(){
		$("#modal_wiewrecord .form-control").val("");		
		$("#modal_wiewrecord .form-control.select2").val(-1).change();
	}
	
	
	$(document).ready(function(){		
		bloquear_handler( true );		
		{if $btn_add == TRUE}
			
		if( typeof addrecord_handler !== "undefined" ){
			if( jQuery.isFunction( addrecord_handler ) )
				$('#btn_add').click(addrecord_handler);
		}
		if( typeof saverecord_handler !== "undefined" ){
			if( jQuery.isFunction( saverecord_handler ) )
				$('#btn_save').click(saverecord_handler);
		}
		
		{/if}
	});


</script>
{/if}


{if $upload_image == TRUE}
{literal}

<!--Upload Imagen-->
<script>	
$('#imagen').change(cargar_imagen);
function upload_image(e) {
      var files = e.target.files; // FileList object     
      // Obtenemos la imagen del campo "file".
      for (var i = 0, f; f = files[i]; i++) {
        //Solo admitimos imágenes.
        if (!f.type.match('image.*')) {
            continue;
        }
        var reader = new FileReader();
        reader.onload = (function(theFile) {
            return function(e) {
            	$('#imagen_thumb').attr('src',e.target.result);
			};
        })(f);
        reader.readAsDataURL(f);
      }
}
</script>
{/literal}
{/if}

{if $tinymce == TRUE}
<script>
	tinymce.init({
		selector:'#{$tinymce_control}',
		language_url : '{$theme.plugins}tinymce/langs/es.js',
		menu: {
			
		    format: {
		    	title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'
		    },
		    table: {
		    	title: 'Table', items: 'inserttable tableprops deletetable | cell row column'
		    },

		},
		plugins: [
		    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
		    'searchreplace wordcount visualblocks visualchars code fullscreen',
		    'insertdatetime media nonbreaking save table contextmenu directionality',
		    'emoticons template paste textcolor colorpicker textpattern imagetools'
		],
		toolbar1: 'insertfile undo redo | styleselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen | preview visualblocks searchreplace code | media image emoticons  | link charmap pagebreak anchor hr insertdatetime',
	});
</script>
{/if}
