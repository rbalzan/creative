<?php

require PATH_ROOT.'/vendor/autoload.php';
require 'Console.php';
require 'Router.php';
require 'Request.php';

$command = Router::execute( new Request() );

$console = new ConsoleKit\Console();
$console->addCommand($command);
$console->run();


?>