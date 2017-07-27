{include file="includes/auth.head.tpl"}

<style>
    body {
        background: #f1f1f1 url({$theme.backend.images}accounts.sign.jpg) no-repeat center center fixed !important;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    
    .login-box {
        margin-top: 100px;
        border: 1px solid #eaecf0;
        border-radius: 2px
    }
</style>

<body style="background-color: #f1f1f1">

    {include file=$_html nocache}

</body>

{include file="includes/auth.footer.tpl"}