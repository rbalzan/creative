<div ng-controller="data as showCase">
	<div class="box box-default">
		<div class="box-header with-border">
			<h4 class="box-title">Mi Perfil</h4>
		</div>
		<div id="panelbox-content" class="box-body">
		

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="text-uppercase active"><a href="#profile" data-toggle="tab" aria-expanded="true">Pefil</a></li>
                    <li class="text-uppercase"><a href="#security" data-toggle="tab" aria-expanded="false">Seguridad</a></li>
                </ul>
                <div class="tab-content">
                    <!--Tab-->
                    <div class="tab-pane active" id="profile">

                        <form id="panelbox_content_form_profile" class="form-view" action="" method="post" enctype="multipart/form-data" data-success="" data-toastr-position="top-right">
                            <fieldset>

                                <div class="col-md-4 ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <output id="image_content" class="text-center">
                                                <img id="image_img" src="{$data.image|default:'/content/themes/iune/images/user.backend.png'}" width="200" height="200" class="img-responsive thumbnail img-circle" style="background-color: #fff;border-radius: 50% !important;margin: 0 auto; width:200px; height:200px">
                                            </output>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="fileupload-buttonbar">
                                                <span class="btn btn-success fileinput-button">
                                                    <span class="fa fa-file-image-o"></span>
                                                    <span>Seleccione</span>
                                                    <input id="image" type="file" name="image[]" accept="image/jpg, image/jpeg, image/png, image/gif">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6" style="margin-bottom:5px">
                                            <label for="dni">Cédula <span class="fa fa-circle" style="font-size: 6px; color: #ce0000" data-toggle="tooltip" data-placement="top" title="Este campo es requerido"></span> </label>
                                            <input id="dni" type="text" class="form-control required" required value="{$data.dni}">
                                        </div>
                                        <div class="col-sm-6 col-md-6" style="margin-bottom:5px">
                                            <label for="name">Nombre <span class="fa fa-circle" style="font-size: 6px; color: #ce0000" data-toggle="tooltip" data-placement="top" title="Este campo es requerido"></span> </label>
                                            <input id="name" type="text" class="form-control required" required value="{$data.name}">
                                        </div>
                                        <div class="col-sm-6 col-md-6" style="margin-bottom:5px">
                                            <label for="last_name">Apellido <span class="fa fa-circle" style="font-size: 6px; color: #ce0000" data-toggle="tooltip" data-placement="top" title="Este campo es requerido"></span> </label>
                                            <input id="last_name" type="text" class="form-control required" required value="{$data.last_name}">
                                        </div>
                                        <!--<div class="col-sm-6 col-md-6"  style="margin-bottom:5px">
                                            <label for="nicname">Alias <span class="fa fa-circle" style="font-size: 6px; color: #ce0000" data-toggle="tooltip" data-placement="top" title="Este campo es requerido"></span> </label>
                                            <input id="nicname" type="text" class="form-control required" required value="{$data.nicname}">
                                        </div>-->
                                        <div class="col-sm-6 col-md-6" style="margin-bottom:5px">
                                            <label for="email">Correo corporativo</label>
                                            <input id="email" type="text" class="form-control required" readonly required value="{$data.email}">
                                        </div>
                                     </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right" style="margin:15px auto; ">
                                            <button id="btn_save_profile" type="submit" class="btn btn-info btn-lg" {html::tooltips("Guardar cambios en mi perfil")} style="height:46px"><span class="fa fa-save"></span> Guardar</button>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                        </form>

                    </div>

                    <div class="tab-pane" id="security">

                        
                        
                        <form id="panelbox_content_form_security" class="form-view" action="" method="post" enctype="multipart/form-data" data-success="" data-toastr-position="top-right">
                            <fieldset>
                                <div class="col-md-4 text-center">
                                    <span class="fa fa-key" style="font-size:100px"></span>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                       <div class="col-sm-6 col-md-6" style="margin-bottom:5px">
                                            <label for="pass">Contraseña <span class="fa fa-circle" style="font-size: 6px; color: #ce0000" data-toggle="tooltip" data-placement="top" title="Este campo es requerido"></span> </label>
                                            <input id="pass1" type="password" class="form-control required" required>
                                        </div>
                                        <div class="col-sm-6 col-md-6" style="margin-bottom:5px">
                                            <label for="pass2">Repita contraseña <span class="fa fa-circle" style="font-size: 6px; color: #ce0000" data-toggle="tooltip" data-placement="top" title="Este campo es requerido"></span> </label>
                                            <input id="pass2" type="password" class="form-control required" required>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 text-right" style="margin:15px auto; ">
                                        <button id="btn_save_security" type="submit" class="btn btn-info btn-lg" {html::tooltips("Guardar cambios en mi perfil")} style="height:46px"><span class="fa fa-save"></span> Guardar</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>

                        
                        
                        
                        
                        
                    </div>
                </div>
            </div>
                
           
		</div><!-- /.box-body -->
	</div><!-- /.box -->
</div>



<script>	

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
            	$('#image_img').attr('src',e.target.result);
			};
        })(f);
        reader.readAsDataURL(f);
      }
}
</script>


<script>
	
/**
* Envia una peticion para crear un nuevo registro
*/
function saverecord_handler( e ){
	
	e.preventDefault()
	
	var $btn = $('#btn_save_profile');
	$(".form-control").parent().removeClass("has-error");
	
    var data = new FormData();
        data.append('image', $('#image')[0].files[0]);
        
	
    $.each( $('#panelbox_content_form_profile .form-control'), function(index, item){
        var name = $(item).prop('id'),
            value = $(item).val();
            data.append(name, value);
    });
    
	$(".form-control").parent().removeClass("has-error");
		
	$.ajax({
		url : '/api/v1/accounts.json/profile/update/',
		data : data,
        processData: false,
      	contentType: false,
	    beforeSend: function( e ) {
			$.isLoading({ text: "Procesando..." });
		},
		type : "POST",		 
		dataType : "JSON",		 
		success : function(data) {
			
			$.isLoading( "hide" );
			_token = data.token;
			console.log(data.statusText);
			
			
			//Unauthorized - Indica que el cliente debe estar autorizado primero antes de realizar operaciones con los recursos
			if( data.status == 401 ){
	    		notify(data.statusText, data.icon);
	    		return false;
	    	}
	    	
	    	//Unprocessable Entity - Parametros incorrectos
			if( data.status == 422 ){
	    		notify(data.statusText, data.icon);
	    		$(data.field).focus().parent().addClass("has-error");
	    		return false;
	    	}
	    	
	    	//Internal Server Error
	    	if( data.status == 500 ){
	    		notify(data.statusText, data.icon);
	    		return false;
	    	}
	    	
	    	//Created - Creado con exito
	    	if( data.status == 201 ){
                $('.user-image').attr('src', data.data.image + '?token=' + Math.random() );
              
	    		notify(data.statusText, data.icon);
			}
			
			
	    }
	});
	
}


/**
* Envia una peticion para crear un nuevo registro
*/
function saverecord_security_handler( e ){
	
	e.preventDefault()
	
	var $btn = $('#btn_save_security');
	$(".form-control").parent().removeClass("has-error");
	
    var data = {};
    
    $.each( $('#panelbox_content_form_security .form-control'), function(index, item){
        var name = $(item).prop('id'),
            value = $(item).val();
            data[name] = value;
    });
    
	$(".form-control").parent().removeClass("has-error");
		
	$.ajax({
		url : '/api/v1/accounts.json/profile/security/',
		data : data,
	    beforeSend: function( e ) {
			$.isLoading({ text: "Procesando..." });
		},
		type : "POST",		 
		dataType : "JSON",		 
		success : function(data) {
			
			$.isLoading( "hide" );
			_token = data.token;
			console.log(data.statusText);
			
			
			//Unauthorized - Indica que el cliente debe estar autorizado primero antes de realizar operaciones con los recursos
			if( data.status == 401 ){
	    		notify(data.statusText, data.icon);
	    		return false;
	    	}
	    	
	    	//Unprocessable Entity - Parametros incorrectos
			if( data.status == 422 ){
	    		notify(data.statusText, data.icon);
	    		$(data.field).focus().parent().addClass("has-error");
	    		return false;
	    	}
	    	
	    	//Internal Server Error
	    	if( data.status == 500 ){
	    		notify(data.statusText, data.icon);
	    		return false;
	    	}
	    	
	    	//Created - Creado con exito
	    	if( data.status == 201 ){
	    		notify(data.statusText, data.icon);
                return true;
			}
			
			
	    }
	});
	
}


$(document).ready(function(){
    $('#image').change(upload_image);
	$('#panelbox_content_form_profile').submit(saverecord_handler);
    $('#panelbox_content_form_security').submit(saverecord_security_handler);
});	
	
</script>