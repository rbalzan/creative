<?php

abstract class RegisterShutdownFunction
{
  // just a container
    static $function;

    public static function execute() {

        header("Connection: close");
        $size = ob_get_length();
        header("Content-Length: $size");
        
        ob_end_flush();
        flush();
        call_user_func(RegisterShutdownFunction::$func);

    }
}

?>