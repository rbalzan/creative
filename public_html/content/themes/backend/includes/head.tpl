<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="utf-8">
    <title>{$titulo|default:$app->company_name}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1" name="viewport">

    <link rel="canonical" href="{$BASE_URL}" />
    <link rel="icon" type="image/png" href="/content/brand/favicon.ico" />

    <meta name="robots" content="NOINDEX,NOFALLOW">

    <script type="text/javascript" src="{$assets.components}jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap and Font Awesome css -->
    <link rel="stylesheet" href="{$assets.components}bootstrap/dist/css/bootstrap.css" type="text/css" />
    <script type="text/javascript" src="{$assets.components}bootstrap/dist/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="{$assets.components}font-awesome/css/font-awesome.min.css" type="text/css" />
   

    {if $angular}l
    <script type="text/javascript" src="{$assets.js}angular.min.js"></script>
    <script type="text/javascript" src="{$assets.js}angular-resources/ngResources.js"></script>
    <script type="text/javascript" src="{$assets.js}angular-data-table/angular-datatables.min.js"></script>
    <link rel="stylesheet" href="{$assets.js}angular-data-table/css/angular-datatables.css">
    <script type="text/javascript" src="{$view.js}{$ng_module}.ng.js"></script>
    {/if}

    <script type="text/javascript" src="{$assets.components}extendjs/dist/js/extendjs.js"></script>

    <!-- Responsivity for older IE -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

    {if isset($page.js) && count($page.js)} {foreach $page.js as $key => $value} {if $value.in_head }{* *}
    <script src="{$value.src}" type="text/javascript"></script>{* *}{/if} {/foreach} {/if}{* *}{if isset($page.css) && count($page.css)} {foreach $page.css as $key => $value} {if $value.in_head }{* *}
    <link href="{$value.src}" rel="stylesheet"></link>{* *}{/if} {/foreach} {/if}
    <link rel="stylesheet" href="{$theme.css}style.css" type="text/css" />
    <link rel="stylesheet" href="{$theme.css}skins/skin-turquoise.css">
    <link rel="stylesheet" href="{$theme.css}custom.css" type="text/css" />
</head>