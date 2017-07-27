<?php
abstract class Lang 
{
    private static $lang_active;

    /**
     * Undocumented function
     *
     * @param [type] $message
     * @param [type] $option
     * @return void
     */
    public static function get($message, $option = NULL)
    {
        if( strpos($message,'.') !== FALSE ){
            $file = explode('.', $message)[0];
            $message = explode('.', $message)[1];

            if( file_exists(PATH_APP .DS. 'langs' .DS. self::$lang_active .DS. $file.'.php') ){
                $messages = include PATH_APP .DS. 'langs' .DS. self::$lang_active .DS. $file.'.php';
                return $messages[$message];
            } else return '';
            
        } else {
            if( file_exists(PATH_APP .DS. 'langs' .DS. self::$lang_active .DS. 'default.php') ){
                $messages = include PATH_APP .DS. 'langs' .DS. self::$lang_active .DS. 'default.php';
                return $messages[$message];
            } else return '';
            
        }
        
    }

    /**
     * Undocumented function
     *
     * @param [type] $lang
     * @return void
     */
    public static function set_lang( $lang = NULL){
        self::$lang_active = $lang;        
    }
}

function l($message, $option = NULL){
    return Lang::get($message, $option);   
}

?>