{assign var=backend value=Session::get('backend')}

<body {if $angular}ng-app="{$ng_module}" {/if} class="skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{$app->company_name}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{$app->company_name}</span>
            </a>

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">


                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{$theme.images|cat:'user.backend.png'}" class="user-image img-circle" alt="" style="background-color: #fff">
                                <span class="hidden-xs">{$backend.description|upper}&ensp;</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{$theme.images|cat:'user.backend.png'}" class="img-circle" alt="" style="background-color: #fff">

                                    <p>
                                        {$backend.description|upper}
                                        <small>{if $backend.profile_name}{$backend.profile_name}{/if}</small>
                                    </p>
                                </li>
                                <!-- Menu Body --
               <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="/accounts/myprofile/?tokenurl={hash_url()}" class="btn btn-default btn-flat">Mi Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="/accounts/signout/?tokenurl={hash_url()}" class="btn btn-default btn-flat">Salir</a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" data-toggle="control-sidebar"><span class="fa fa-gears"></span></a>
                        </li>
                    </ul>
                </div>

            </nav>
        </header>

        {include file="includes/menu.tpl"}


        <section class="content-wrapper">
            <section class="content">