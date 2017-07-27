</section>
</section>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; {$smarty.now|date_format:"%Y"} {$app->company_name}.</strong> Todos los derechos reservados.
</footer>

</div>

<script>
    var AdminLTEOptions = {
        //Enable sidebar expand on hover effect for sidebar mini
        //This option is forced to true if both the fixed layout and sidebar mini
        //are used together
        sidebarExpandOnHover: true,
        //BoxRefresh Plugin
        enableBoxRefresh: true,
        //Bootstrap.js tooltip
        enableBSToppltip: true
    };
</script>

<script src="{$theme.url}js/script.js"></script>

<!--datepicker-->
<script src="{$assets.components}bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<!--Select2-->
<script type="text/javascript" src="{$assets.components}select2/dist/js/select2.full.min.js"></script>
<link rel="stylesheet" href="{$assets.components}select2/dist/css/select2.min.css">

<!--daterangepicker-->
<script type="text/javascript" src="{$assets.components}moment/min/moment.min.js"></script>
<!--<script type="text/javascript" src="{$assets.components}daterangepicker/lib/daterangepicker/daterangepicker.js"></script>
<!<link rel="stylesheet" href="{$assets.components}daterangepicker/lib/daterangepicker/css/daterangepicker.css">-->

<script src="{$assets.components}jquery.numeric/jquery.numeric.js"></script>

<!-- Data Table-->
<script src="{$assets.components}datatable/media/js/jquery.dataTables.js"></script>
<link href="{$assets.components}datatable/media/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" />

<!-- Toast Message-->
<script src="{$assets.components}jquery.toastmessage/jquery.toastmessage.js"></script>
<link href="{$assets.components}jquery.toastmessage/jquery.dataTables.min.css" type="text/css" rel="stylesheet" />

<!--jquery.upload
<link rel="stylesheet" href="{$assets.components}/jquery.upload/css/jquery.fileupload.css" />
<link rel="stylesheet" href="{$assets.components}jquery.upload/css/jquery.fileupload-ui.css" />
-->
<!--isloading-->
<link rel="stylesheet" href="{$assets.components}isloading/css/jquery.isloading.css" />
<script type="text/javascript" src="{$assets.components}isloading/js/jquery.isloading.js"></script>


<link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway:300,400,700,900" rel="stylesheet" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu:300">
<script src="{$assets.components}bootbox.min.js"></script>


{if isset($page.js) && count($page.js)} {foreach $page.js as $key => $value} {if !$value.in_head }{* *}
<script src="{$value.src}" type="text/javascript"></script>{* *}{/if} {/foreach} {/if}{* *}{if isset($page.css) && count($page.css)} {foreach $page.css as $key => $value} {if !$value.in_head }{* *}
<link href="{$value.src}" rel="stylesheet"></link>{* *}{/if} {/foreach} {/if}

<script>
    $(function() {
        $('.numeric').numeric();
        $(".select2").select2({
            placeholder: "Seleccione..."
        });
        $('[data-toggle="popover"]').popover();
    });
</script>
<script>
</script>
</body>