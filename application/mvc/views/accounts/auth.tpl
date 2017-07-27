<div class="container">

    <div class="login-box">
        <div class="login-box-body">

            <div class="login-logo" style="margin-top: 15px">
                <h1 style="margin:10px 0; text-transform: uppercase; font-weight: 300">Incio de sesión</h1>
            </div>

            <form id="form-auth" class="form-view" action="" method="post" enctype="multipart/form-data" data-success="" data-toastr-position="top-right">
                <fieldset>
                    <div class="form-group has-feedback">
                        <label for="email">Dirección de correo</label>
                        <input id="email" type="email" class="form-control" required placeholder="Email" value="brincon@megacreativo.com">
                        <span class="fa fa-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="pass">Contraseña</label>
                        <input id="pass" type="password" class="form-control" required placeholder="Contraseña" value="brincon@megacreativo.com">
                        <span class="fa fa-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="/accounts/reset_password/" class="pull-right">Recuperar contraseña</a>
                        </div>
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
    <div>
        {if $ambit=='backend'}
        <a href="/accounts/auth/" class="link-admin">Regresar</a> {else}
        <a href="/accounts/auth/backend/" class="link-admin">Administración</a> {/if}
    </div>
</div>

<style>
    .link-admin {
        position: absolute;
        bottom: 15px;
        right: 20px;
    }
</style>

{if $ambit=='backend'}
<script>
    var backend = 1;
</script>
{else}
<script>
    var backend = 0;
</script>
{/if}

<script>
    var _token = '{$token|default:""}';

    $('#form-auth').submit(function(e) {

        e.preventDefault();

        $('.form-group').removeClass('has-error');

        if ($('#email').val() == '') {
            $('#email').focus().parent().addClass('has-error');
            notify('Debe ingresar todos los datos para continuar', 'error');
            return false;
        }

        if ($('#pass').val() == '') {
            $('#pass').focus().parent().addClass('has-error');
            notify('Debe ingresar todos los datos para continuar', 'error');
            return false;
        }

        var $btn = $('#btn_acceso').button('loading');

        $.ajax({
            url: '/api/v1/accounts/auth/',
            data: {
                email: $('#email').val(),
                pass: $('#pass').val(),
                token: _token,
                backend: backend,
            },
            type: 'POST',
            dataType: 'json',
            success: function(data) {

                if (data.status == 200) {
                    setTimeout(function() {
                        location.href = data.default_module;
                    }, 1000);
                    $('.login-box').fadeOut();
                    notify(data.statusText, data.icon);
                } else {
                    $btn.button('reset');
                    notify(data.statusText, data.icon);
                    $(data.field).focus().parent().addClass('has-error');
                    //_token = data.response.token;				
                }
            }
        });

    });
</script>