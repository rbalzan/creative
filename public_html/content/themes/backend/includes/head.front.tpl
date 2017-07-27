<!DOCTYPE html>
<html lang="es-ES">

<head>
	<meta charset="utf-8">
	<title>{$title|default:""}</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1" name="viewport">		
	<meta name="description" content="{$meta_description}" />
	<meta name="keywords" content="{$meta_keywords}"/>	

	<meta property="og:locale" content="{$lang}" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="{$titulo|default:''}" />
	<meta property="og:description" content="{$meta_description|default:""}" />
	<meta property="og:url" content="{BASE_URL}" />
	<meta property="og:site_name" content="{$name}" />
	{if $brand.image}<meta property="og:image" content="{$brand.image}" />{/if}
	<meta property="og:updated_time" content="{$updated_time|default:""}">
	
	{if $twitter}
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:description" content="{$meta_description}" />
	<meta name="twitter:title" content="{$titulo|default:''}" />
	<meta name="twitter:site" content="@{$twitter_site}" />
	<meta name="twitter:image" content="{$twitter_image}" />
	<meta name="twitter:creator" content="@{$twitter_creator}" />
	{/if}
	
	{if $twitter}
	<meta property="article:section" content="{$article.section}'">
	<meta property="article:published_time" content="{$article.published_time}">
	<meta property="article:modified_time" content="{$article.modified_time}"
	{/if}
	
	<link rel="canonical" href="{BASE_URL}"/>
	<link rel="icon" type="image/png" href="{$favicon}" />

	<meta name="robots" content="">
	
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700|Philosopher:400,700" rel="stylesheet">
	
    <!-- Bootstrap and Font Awesome css -->
   	<link rel="stylesheet" href="{$asset.plugins}bootstrap/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="{$asset.plugins}font-awesome/css/font-awesome.min.css" type="text/css" />
	<link rel="stylesheet" href="{$theme.css}animate.css" type="text/css" />
	
	<!-- Framewok CSS -->
	<link rel="stylesheet" href="{$theme.css}essentials.css" type="text/css" />
	<link rel="stylesheet" href="{$theme.css}header.css" type="text/css" />
	
	<link rel="stylesheet" href="{$theme.css}style.front.css" type="text/css" />
	<link rel="stylesheet" href="{$theme.css}color.css" type="text/css" />
	<link rel="stylesheet" href="{$theme.css}custom.front.css" type="text/css" />
	
	<script type="text/javascript" src="{$asset.plugins}jquery/jquery.min.js"></script>
	<script type="text/javascript" src="{$asset.js}jcore.js"></script>
	
{if isset($page.js) && count($page.js)}
{foreach $page.js as $key => $value}
{if $value.in_head}
<script src="{$value.src}" type="text/javascript"></script>
{/if}
{/foreach}
{/if}

{if isset($page.css) && count($page.css)}
{foreach $page.css as $key => $value}
{if $value.in_head }
<link href="{$value.src}" rel="stylesheet"></link>
{/if}
{/foreach}
{/if}

</head>