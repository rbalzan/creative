  	</section>
  </section>
  
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 4.0
    </div>
    <strong>Copyright &copy; {$smarty.now|date_format:"%Y"} {if URL != ''}<a href="{URL}">{COMPANY_NAME}</a>{else}{COMPANY_NAME}{/if}.</strong> Todos los derechos reservados.
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

<script src="{$theme.url}js/script.back.js"></script>

<script src="{$theme.plugins}jquery.numeric/jquery.numeric.js"></script>

<!-- Data Table-->
<script src="{$theme.plugins}datatable/js/jquery.dataTables.js"></script>
<link href="{$theme.plugins}datatable/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet"/>

<!-- Toast Message-->
<script src="{$theme.plugins}jquery.toastmessage/jquery.toastmessage.js"></script>
<link href="{$theme.plugins}jquery.toastmessage/jquery.toastmessage.css" type="text/css" rel="stylesheet"/>

<!--jquery.upload-->
<link rel="stylesheet" href="{$theme.plugins}/jquery.upload/css/jquery.fileupload.css"/>
<link rel="stylesheet" href="{$theme.plugins}jquery.upload/css/jquery.fileupload-ui.css"/>
	
<!--isloading-->
<link rel="stylesheet" href="{$theme.plugins}isloading/css/jquery.isloading.css"/>
<script type="text/javascript" src="{$theme.plugins}isloading/js/jquery.isloading.js"></script>
	

<link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway:300,400,700,900" rel="stylesheet" />

<script src="{$asset.plugins}bootbox.min.js"></script>


{if isset($page.js) && count($page.js)}
{foreach $page.js as $key => $value}
{if !$value.in_head }{*
*}<script src="{$value.src}" type="text/javascript"></script>{*
*}{/if}
{/foreach}
{/if}{*

*}{if isset($page.css) && count($page.css)}
{foreach $page.css as $key => $value}
{if !$value.in_head }{*
*}<link href="{$value.src}" rel="stylesheet"></link>{*
*}{/if}
{/foreach}
{/if}

<script>
$(function(){
	$('.numeric').numeric();
	$(".select2").select2({ placeholder: "Seleccione..." });
	$('[data-toggle="popover"]').popover();
});
</script>
<script>

</script>
</body>