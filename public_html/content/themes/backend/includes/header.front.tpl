{assign var=frontend value=Session::get('frontend')}
{assign var=backend value=Session::get('backend')}

<!-- SLIDE TOP 
<div id="slidetop">

	<div class="container">
		
		<div class="row">

			<div class="col-md-4">
				<h6><i class="icon-heart"></i> WHY SMARTY?</h6>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. </p>
			</div>

			<div class="col-md-4">
				<h6><i class="icon-attachment"></i> RECENTLY VISITED</h6>
				<ul class="list-unstyled">
					<li><a href="#"><i class="fa fa-angle-right"></i> Consectetur adipiscing elit amet</a></li>
					<li><a href="#"><i class="fa fa-angle-right"></i> This is a very long text, very very very very very very very very very very very very </a></li>
					<li><a href="#"><i class="fa fa-angle-right"></i> Lorem ipsum dolor sit amet</a></li>
					<li><a href="#"><i class="fa fa-angle-right"></i> Dolor sit amet,consectetur adipiscing elit amet</a></li>
					<li><a href="#"><i class="fa fa-angle-right"></i> Consectetur adipiscing elit amet,consectetur adipiscing elit</a></li>
				</ul>
			</div>

			<div class="col-md-4">
				<h6><i class="icon-envelope"></i> CONTACT INFO</h6>
				<ul class="list-unstyled">
					<li><b>Address:</b> PO Box 21132, Here Weare St, <br /> Melbourne, Vivas 2355 Australia</li>
					<li><b>Phone:</b> 1-800-565-2390</li>
					<li><b>Email:</b> <a href="mailto:support@yourname.com">support@yourname.com</a></li>
				</ul>
			</div>

		</div>

	</div>

	<a class="slidetop-toggle" href="#"></a>

</div>
<!-- /SLIDE TOP -->
		
<div id="header" class="sticky dark header-sm clearfix">

	<!-- SEARCH HEADER --
	<div class="search-box over-header">
		<a id="closeSearch" href="#" class="glyphicon glyphicon-remove"></a>
		<form action="" method="post">
			<input type="text" class="form-control" placeholder="BUSCAR" />
		</form>
	</div> 
	<!-- /SEARCH HEADER -->
	
	
	<!-- TOP NAV -->
	<header id="topNav">
		<div class="container">

			<!-- Mobile Menu Button -->
			<button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
				<i class="fa fa-bars"></i>
			</button>
			<!--
			<ul class="pull-right nav nav-pills nav-second-main">

				<!-- SEARCH --
				<li class="search">
					<a href="javascript:;">
						<i class="fa fa-search"></i>
					</a>
				</li>
				<!-- /SEARCH -->


				<!-- QUICK SHOP CART --
				<li class="quick-cart">
					<a href="#">
						<span class="badge badge-aqua btn-xs badge-corner">2</span>
						<i class="fa fa-shopping-cart"></i> 
					</a>
					<div class="quick-cart-box">
						<h4>Shop Cart</h4>

						<div class="quick-cart-wrapper">

							<a href="#"><!-- cart item --
								<img src="assets/images/demo/people/300x300/4-min.jpg" width="45" height="45" alt="" />
								<h6><span>2x</span> RED BAG WITH HUGE POCKETS</h6>
								<small>$37.21</small>
							</a><!-- /cart item --

							<a href="#"><!-- cart item --
								<img src="assets/images/demo/people/300x300/5-min.jpg" width="45" height="45" alt="" />
								<h6><span>2x</span> THIS IS A VERY LONG TEXT AND WILL BE TRUNCATED</h6>
								<small>$17.18</small>
							</a><!-- /cart item -->

							<!-- cart no items example --
							<!--
							<a class="text-center" href="#">
								<h6>0 ITEMS ON YOUR CART</h6>
							</a>
							--

						</div>

						<!-- quick cart footer --
						<div class="quick-cart-footer clearfix">
							<a href="shop-cart.html" class="btn btn-primary btn-xs pull-right">VIEW CART</a>
							<span class="pull-left"><strong>TOTAL:</strong> $54.39</span>
						</div>
						<!-- /quick cart footer --

					</div>
				</li>
				<!-- /QUICK SHOP CART

			</ul>
			 -->
			<a class="logo pull-left" href="{BASE_URL}" style="padding-top:3px">
				<img src="{$brand.logo}" class="img-responsive" alt="MEGA CREATIVO">
			</a>
			
			<div class="navbar-collapse pull-right nav-main-collapse collapse nopadding-left nopadding-right " aria-expanded="false">
				<nav class="nav-main">
					<ul id="topMain" class="nav nav-pills nav-main">
						
						<li>
							<a href="{BASE_URL}">INICIO</a>
						</li>
						
						<li class="dropdown">
							<a class="dropdown-toggle" href="#">
								DOMINIOS
							</a>
							<ul class="dropdown-menu">
								<li><a href="/dominios/registrar/">Registra tu dominio</a></li>
							<!--	<li><a href="/dominios/ve/">Dominios .com.ve</a></li>-->
								<li><a href="/whois/">Consulta WHOIS</a></li>
								<!--<li><a href="/dominios/gratis/">Dominios Gratis</a></li>-->
							</ul>
						</li>
						
						<li class="dropdown">
							<a class="dropdown-toggle" href="#">
								HOSTING WEB
							</a>
							<ul class="dropdown-menu">
								<li><a href="/hosting-linux/">Hosting Linux</a></li>
								<li><a href="/hosting-windows/">Hosting Windows</a></li>
								<li><a href="/hosting/vps/">Servidores Virtuales</a></li>
								<li><a href="/hosting/dedicados/">Serviodres Dedicados</a></li>
								<li><a href="/hosting/streaming/">Streaming</a></li>
							</ul>
						</li>
						
						<li class="dropdown"><!-- PRODUCTOS -->
							<a class="dropdown-toggle" href="#">
								PRODUCTOS
							</a>
							<ul class="dropdown-menu">
								<li><a href="/productos/wanent/">Pedidos WANENT</a></li>
							</ul>
						</li>
						
						<li class="dropdown"><!-- PRODUCTOS -->
							<a class="dropdown-toggle" href="#">
								SERVICIOS
							</a>
							<ul class="dropdown-menu">
								<li><a href="/servicios/pagina-web/">Páginas Web</a></li>
								<li><a href="/servicios/pagina-web/">Aplicaciones Móviles</a></li>
								<li><a href="/servicios/pagina-web/">Desarrollo de Software</a></li>
							</ul>
						</li>
						
						
						
						<li class="dropdown"><!-- CONTACTO -->
							<a class="" href="/contacto/">
								CONTACTO
							</a>
						</li>
						
						
					</ul>

				</nav>
			</div>

		</div>
	</header>
	<!-- /Top Nav -->

</div>