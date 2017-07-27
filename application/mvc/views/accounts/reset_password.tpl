<style>
body{
	background: #f1f1f1 url({$theme.images}accounts.sign.jpg) no-repeat center center fixed !important;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
.login-box{
	margin-top: 100px;
	border: 1px solid #eaecf0;
	border-radius: 2px
}
</style>

<div class="container">
	
	<div class="login-box">
		<div class="login-box-body">
	  
			<div class="login-logo" style="margin-top: 15px">
				<h1 style="margin:10px 0; text-transform: uppercase; font-weight: 300">Recupera tu contraseña</h1>
			</div>
		  
			<form id="form-sign" class="form-view" action="" method="post" enctype="multipart/form-data" data-success="" data-toastr-position="top-right">
				<fieldset>
					<div class="form-group has-feedback">
						<label for="email">Dirección de correo</label>
						<input id="email" type="email" class="form-control" placeholder="Email" value="brincon@megacreativo.com">
						<span class="fa fa-envelope form-control-feedback"></span>
					</div>
					<br/>
					<div class="row">
						<div class="pull-right">
						    <div class="col-md-12">
							    <button id="btn_acceso" type="submit" class="btn btn-info btn-block btn-flat" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Procesando..."><span class="fa fa-sign-in"></span> Enviar</button>
						    </div>
						</div>
					</div>
				</fieldset>
			</form>
		</div>

	</div>

</div>



<script>  
var _token = '{$token}';

$('#form-sign').submit(function(e){	
	
	e.preventDefault();
	
	$('.form-group').removeClass('has-error');
	
	if( $('#email').val()=='' ){
		$('#email').focus().parent().addClass('has-error');
		notify('Debe ingresar todos los datos para continuar', 'error');
		return false;
	}
	
	var $btn = $('#btn_acceso').button('loading');
	
	$.ajax({
	    url : '/api/v1/accounts/reset_password/',
	    data : {
	    	email 	: $('#email').val(),
	    	token	: _token,
	    },		 
	    type : 'POST',		 
	    dataType : 'json',
	    success : function(data) {
			//_token = data.response.token;
			$btn.button('reset');
			notify(data.statusText, data.icon);
	    }
	});
});
</script>
	