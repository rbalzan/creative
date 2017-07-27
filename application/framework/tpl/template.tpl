<!doctype html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Creative</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Ubuntu:300" rel="stylesheet">
        <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

        <!-- Styles -->
        <style>
            html, body {
                background-color: #f1f1f1;
                color: #666;
                font-family: "Ubuntu", sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 90px;
                margin-bottom: 30px;
            }

            .links > a {
                color: #666;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
			.header{
				background-color:#333;
                color:#ddd;
                padding:5px 0px;
                height:30px;
                width:100%;
                display:block;

                
			}
            .header strong {
                padding:5px 20px;
                display:block
            }
        </style>
    </head>
    <body>
        @content
    </body>
</html>