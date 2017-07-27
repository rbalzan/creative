<style>
.user-panel > .info > a {
    text-decoration: none;
    padding-right: 5px;
    margin-top: 3px;
    font-size: 13px;
}
</style>

<aside class="main-sidebar">
<section class="sidebar">
	<div class="user-panel" style="min-height: 60px">
		<div class="pull-left image">
			<img id="user_img_menu" src="{$theme.images|cat:'user.backend.png'}" class="user-image img-circle" alt="" style="background-color: #fff">
		</div>
		<div class="info">
			<p style="margin-top: 4px;margin-bottom: 1px;">{$backend.profile_name}</p>
			<a href="#"><span class="fa fa-circle" style="color:#56b726"></span> En línea</a>
		</div>
	</div> 
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

	</ul>
</section>
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