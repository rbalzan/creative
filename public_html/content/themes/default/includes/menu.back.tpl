<style>
.user-panel > .info > a {
    text-decoration: none;
    padding-right: 5px;
    margin-top: 3px;
    font-size: 13px;
}
</style>
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  
	<div class="user-panel" style="min-height: 60px">
		<div class="pull-left image">
			<img id="user_img_menu" src="{$theme.images|cat:'user.backend.png'}" class="user-image img-circle" alt="" style="background-color: #fff">
		</div>
		<div class="info">
			<p style="margin-top: 4px;margin-bottom: 1px;">{$backend.profile_name}</p>
			<a href="#"><span class="fa fa-circle" style="color:#56b726"></span> En línea</a>
		</div>
	</div>  

  <!-- /.search form -->
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu">

	{if isset($menus.category.backend) && count($menus.category.backend)}
		{foreach $menus.category.backend as $key => $value}
			<li class="header">{$value|upper}</li>
			
			{if isset($menus.menus) && count($menus.menus)}
				{foreach $menus.menus as $k => $menu}
					{if $key == $menu.category}

						{if count($menu.childs)}
							<li id="cliente" class="treeview">
								<a href="#">
									<i class="{$menu.icon}"></i>
									<span>{$menu.title}</span>
									<span class="pull-right-container">
										<span class="fa fa-angle-left pull-right"></span>
									</span>
								</a>
								<ul class="treeview-menu">
									{foreach $menu.childs as $k => $submenu}
										<li id="perfil">
											{link_backend($submenu.url, $submenu.title, $submenu.icon)}
										</li>
									{/foreach}							
								</ul>
							</li>
						{else}
							<li>{link_backend($menu.url, $menu.title, $menu.icon)}</li>
						{/if}

					{/if}
				{/foreach}
			{/if}
	
		{/foreach}
	{/if}
  
  	
  
  
  <!--
  
    <li class="header">MENÚ PRINCIPAL</li>
    
    <li>
		<a href="{url_backend('')}">
			<span class="fa fa-dashboard"></span> <span>Inicio</span>
		</a>
    </li>
    
    
    
    
    
    
	<li class="header">CONTROL DE ESTUDIO</li>
	<li>
		<a href="{url_backend('carreras')}">
			<span class="fa fa-dashboard"></span> <span>Carreras</span>
		</a>
    </li>
	<li>
		<a href="{url_backend('estudiantes')}">
			<span class="fa fa-users"></span> <span>Estudiantes</span>
		</a>
    </li>
    <li>
		<a href="{url_backend('materias')}">
			<span class="fa fa-users"></span> <span>Materias</span>
		</a>
    </li>
    
	<li>
		<a href="{url_backend('profesores')}">
			<span class="fa fa-users"></span> <span>Profesores</span>
		</a>
    </li>
    
    <li class="header">ADMINISTRACIÓN</li>
    <li>
		<a href="{url_backend('pagos')}">
			<span class="fa fa-users"></span> <span>Pagos</span>
		</a>
    </li>
    <li>
		<a href="{url_backend('sedes')}">
			<span class="fa fa-users"></span> <span>Sedes</span>
		</a>
    </li>
    
    
	{if $acl->access_view('users')}
	<li id="cliente" class="treeview">
		<a href="#">
			<i class="fa fa-users"></i>
			<span>Clientes</span>
			<span class="pull-right-container">
				<span class="fa fa-angle-left pull-right"></span>
			</span>
		</a>
		<ul class="treeview-menu">
			<li id="perfil"><a href="/backend/clientes/?token={hash_url()}"><span class="fa fa-caret-right"></span> Clientes</a></li>
			<li id="perfil"><a href="/backend/clientemcargo/?token={hash_url()}"><span class="fa fa-caret-right"></span> Manager Cargo</a></li>
		</ul>
	</li>
	{/if}
    -->

    
  </ul>
</section>
<!-- /.sidebar -->
</aside>


<script>

{if isset($current) && count($current)}
$(document).ready(function(){
	{foreach $current as $key => $value}	
	$('#{$value}').addClass('active');	
	{/foreach}
});
{/if}

</script>