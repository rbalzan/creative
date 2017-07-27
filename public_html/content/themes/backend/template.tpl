{include file="includes/head.tpl"} {include file="includes/header.tpl"}

<style>
    .skin-blue .sidebar-menu>li>a {
        border-left: 3px solid transparent;
        font-weight: 300;
        padding: 8px 5px 8px 15px;
    }
    
    .sidebar-menu>li {
        position: relative;
        margin: 0;
        padding: 0;
    }
    
    .skin-blue .sidebar>.sidebar-menu>li {
        font-weight: 300;
        background: #f0f0f0;
    }
    
    .skin-blue .sidebar-menu>li.header {
        color: #858585;
        background: #060606 !important;
        padding: 12px 5px 10px 15px;
        font-weight: 600 !important;
    }
    
    .skin-blue .sidebar a {
        color: #858585 !important;
    }
    
    .skin-blue .wrapper,
    .skin-blue .main-sidebar,
    .skin-blue .left-side {
        background-color: #101010;
    }
    
    .skin-blue .sidebar>.sidebar-menu>li {
        font-weight: 300;
        background: #101010;
    }
    
    .skin-blue .sidebar-menu>li:hover>a,
    .skin-blue .sidebar-menu>li.active>a {
        color: #f1f1f1 !important;
        background: #1f1f1f;
        border-left-color: #00b3df;
    }
    
    .skin-blue .sidebar-menu>li>.treeview-menu {
        margin: 0 1px;
        background: #1f1f1f;
    }
    
    .toast-item p strong {
        text-transform: uppercase;
    }
    
    label.check {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 0px;
        /*padding: 10px 30px;*/
        font-weight: bold;
        cursor: pointer;
        position: relative;
        top: 5px;
    }
    
    label.check:before {
        content: "";
        display: inline-block;
        width: 17px;
        height: 17px;
        margin-right: 10px;
        position: absolute;
        left: 0;
        bottom: 1px;
        background-color: #fff;
        border: 1px solid #ccc;
    }
    
    input.check[type=checkbox] {
        display: none;
    }
    
    label.check:before {
        border-radius: 1px;
    }
    
    input.check[type=checkbox]:checked+label.check:before {
        content: "\2713";
        font-size: 17px;
        color: #00b3df;
        text-align: center;
        line-height: 17px;
    }
    
    input.check[type=checkbox]:disabled+label.check:before {
        content: "";
        display: inline-block;
        width: 17px;
        height: 17px;
        margin-right: 10px;
        position: absolute;
        left: 0;
        bottom: 1px;
        background-color: #d2d2d2;
        cursor: not-allowed;
    }
    
    input.check[type=checkbox]:checked:disabled+label.check:before {
        content: "\2713";
        font-size: 17px;
        color: #666;
        text-align: center;
        line-height: 17px;
        cursor: not-allowed;
    }
    /**********************************************************/
    
    label.mradio {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        padding: 10px 30px;
        font-weight: bold;
        cursor: pointer;
        position: relative;
        top: 5px;
    }
    
    label.mradio:before {
        content: "";
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 10px;
        position: absolute;
        left: 0;
        bottom: 1px;
        background-color: #666;
        box-shadow: inset 0px 2px 2px 1px rgba(0, 0, 0, .4);
    }
    
    input.mradio[type=radio] {
        display: none;
    }
    
    label.mradio:before {
        border-radius: 50px;
    }
    
    input.mradio[type=radio]:checked+label.mradio:before {
        content: "\2022";
        text-shadow: 1px 1px 1px rgba(0, 0, 0, .4);
        font-size: 20px;
        color: #f1f1f1;
        text-align: center;
        line-height: 20px;
    }
    
    input.mradio[type=radio]:disabled+label.mradio:before {
        content: "";
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 10px;
        position: absolute;
        left: 0;
        bottom: 1px;
        background-color: #999;
        box-shadow: inset 0px 2px 2px 1px rgba(0, 0, 0, .4);
    }
    
    input.mradio[type=radio]:checked:disabled+label.mradio:before {
        content: "\2022";
        text-shadow: 1px 1px 1px rgba(0, 0, 0, .4);
        font-size: 20px;
        color: #d7d7d7;
        text-align: center;
        line-height: 20px;
    }
</style>

<main id="main-content">
    <div id="main-content-section" class="main-section">

        {if $breadcrumbs == true } 
            {include file=$theme.dir|cat:'includes/breadcrumbs.tpl'} 
        {/if} 

        {if strpos($_html , ".tpl") == true } 
            {include file=$_html nocache} 
        {else}
            {$_html} 
        {/if}

        {if isset($echo) && count($echo)} 
            {foreach $echo as $key => $value}
                {eval $value} 
            {/foreach}
        {/if}

    </div>
</main>

{include file="includes/footer.tpl"}
