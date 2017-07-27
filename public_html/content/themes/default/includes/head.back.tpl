<!DOCTYPE html>
<html lang="es-ES">

<head>
	<meta charset="utf-8">
	<title>{$titulo|default:""}</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1" name="viewport">		

	<link rel="canonical" href="{$BASE_URL}"/>
	<link rel="icon" type="image/png" href="/dna-content/brand/favicon.ico" />

	<meta name="robots" content="NOINDEX,NOFALLOW">
    
    <script type="text/javascript" src="{$asset.plugins}jquery/jquery.js"></script>
    	
    <!-- Bootstrap and Font Awesome css -->
   	<link rel="stylesheet" href="{$asset.plugins}bootstrap/css/bootstrap.css" type="text/css" />
	<script type="text/javascript" src="{$theme.plugins}bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" href="{$asset.plugins}font-awesome/css/font-awesome.min.css" type="text/css" />
	
	<!--datepicker-->
	<script src="{$theme.plugins}datepicker/bootstrap-datepicker.js"></script>


	<!--daterangepicker-->
	<script type="text/javascript" src="{$theme.plugins}daterangepicker/moment.min.js"></script>
	<script type="text/javascript" src="{$theme.plugins}daterangepicker/daterangepicker.js"></script>
	<link rel="stylesheet" href="{$theme.plugins}daterangepicker/daterangepicker.css">	
	
	<!--Select2-->
	<script type="text/javascript" src="{$theme.plugins}select2/select2.full.min.js"></script>
	<link rel="stylesheet" href="{$theme.plugins}select2/select2.min.css">

	<script type="text/javascript" src="{$asset.js}jcore.js"></script>
	
	{if $angular}
	<script type="text/javascript" src="{$asset.js}angular.min.js"></script>	
	<script type="text/javascript" src="{$asset.js}angular-resources/ngResources.js"></script>
	<script type="text/javascript" src="{$asset.js}angular-data-table/angular-datatables.min.js"></script>
	<link rel="stylesheet" href="{$asset.js}angular-data-table/css/angular-datatables.css">	
	<script type="text/javascript" src="{$view.js}{$ng_module}.ng.js"></script>	
	{/if}
	
    <!-- Responsivity for older IE -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

{if isset($page.js) && count($page.js)}
{foreach $page.js as $key => $value}
{if $value.in_head }{*
*}<script src="{$value.src}" type="text/javascript"></script>{*
*}{/if}
{/foreach}
{/if}{*

*}{if isset($page.css) && count($page.css)}
{foreach $page.css as $key => $value}
{if $value.in_head }{*
*}<link href="{$value.src}" rel="stylesheet"></link>{*
*}{/if}
{/foreach}
{/if}
	<link rel="stylesheet" href="{$theme.css}style.back.css" type="text/css" />
	<link rel="stylesheet" href="{$theme.css}skins/{if $skin == 'light'}skin-blue-light{else}skin-turquoise{/if}.css">
	<link rel="stylesheet" href="{$theme.css}custom.back.css" type="text/css" />
</head>